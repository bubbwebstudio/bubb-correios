<?php

namespace BUBB\Correios;

use BUBB\Correios\Exceptions\ZipcodeException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Zipcode
{

	const VIACEP_URL = 'https://viacep.com.br/ws/%s/json';

	const PAGARME_URL = 'https://api.pagar.me/1/zipcodes/%s';

	protected $zipcode;

	public function getZipcode()
	{
		return $this->zipcode;
	}

	public function __construct($zipcode)
	{
		$this->zipcode = preg_replace('/[^0-9]/', '', $zipcode);
	}

	/**
	 * get
	 * Return zipcode data
	 * @return array
	 */
	public function get()
	{

		$client = new Client;

		try
		{

			$response = $client->request('GET', sprintf(self::VIACEP_URL, $this->getZipcode()), ['timeout' => 2]);
			$content = json_decode($response->getBody()->getContents(), true);

			if ( isset($content['erro']) )
			    throw new ZipcodeException('CEP inválido', 400);

			return [
				'zipcode' => $this->getZipcode(),
				'street' => $content['logradouro'],
				'neighborhood' => $content['bairro'],
				'city' => $content['localidade'],
				'state' => $content['uf'],
			];

		} catch ( RequestException $e )
		{

			// Get zipcode data in Pagar.me API

			try
			{

				$response = $client->request('GET', sprintf(self::PAGARME_URL, $this->getZipcode()), ['timeout' => 2]);
				$content = json_decode($response->getBody()->getContents(), true);

				return [
					'zipcode' => $this->getZipcode(),
					'street' => $content['street'],
					'neighborhood' => $content['neighborhood'],
					'city' => $content['city'],
					'state' => $content['state'],
				];

			} catch ( RequestException $e )
			{
				if ( $e->getCode() == 404 )
					throw new ZipcodeException('CEP inválido', 400);
				
				throw new ZipcodeException($e->getMessage(), $e->getCode());
			}
			
		}

	}
	

}