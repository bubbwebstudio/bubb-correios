<?php

namespace BUBB\Correios;

use BUBB\Correios\Exceptions\CorreiosTrackingException;
use Goutte\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class CorreiosTracking
{

	private $trackingCode;

	const TRACKING_URL = 'http://www.linkcorreios.com.br';

	public function setTrackingCode($value)
	{
		return $this->trackingCode = $value;
	}

	public function getTrackingCode()
	{
		return $this->trackingCode;
	}

	public function __construct($trackingCode)
	{
		$this->setTrackingCode($trackingCode);
	}

	public function get()
	{

		try
		{

			$client = new Client;
			$crawler = $client->request('GET', self::TRACKING_URL.'/'.$this->getTrackingCode());
			$arr = [];

			$crawler->filter('div#conteudo')->each(function ($node) use($collection, &$arr)
			{
			  
			  $lastDate = null;

			  $node->filter('table > tbody > tr')->each(function ($n, $key) use($collection, &$arr, &$lastDate)
			    { 

			      $date = $n->filter('td[rowspan="2"]');
			      $locale = null;

			      if ( $date->count() > 0 )
			      {
			        $date = $date->text();
			        $status = strip_tags($n->filter('td[colspan="2"]')->text());
			        $lastDate = $date;

			        $arr[$date] = [
			          'date' => $date,
			          'status' => $status
			        ];

			      }
			      else
			      {
			        $date = null;
			        $status = null;
			        $locale = str_replace('Local: ', '', $n->filter('td[colspan="2"]')->text());

			        $arr[$lastDate]['locale'] = $locale;

			      }

			    });

			});

			$tracking = array_values($arr);

			$trackingObject = array_map(function ($key)
		    {

		        return [
		            'timestamp' => Carbon::createFromFormat('d/m/Y H:i', $key['date'])->timestamp,
		            'date' => Carbon::createFromFormat('d/m/Y H:i', $key['date'])->format('Y-m-d H:i'),
		            'locale' => $key['locale'],
		            'status' => $key['status'],
		            'forwarded' => isset($key['encaminhado']) ? $key['encaminhado'] : null,
		            'delivered' => $key['status'] == 'Entrega Efetuada'
		        ];
		    }, $tracking);

			$firstTrackingObject = $trackingObject[0];

			return array_merge(
			    ['code' => $this->getTrackingCode()],
			    ['last_timestamp' => $firstTrackingObject['timestamp']],
			    ['last_status' => $firstTrackingObject['status']],
			    ['last_date' => $firstTrackingObject['date']],
			    ['last_locale' => $firstTrackingObject['locale']],
			    ['delivered' => $firstTrackingObject['delivered']],
			    ['delivered_at' => ($firstTrackingObject['delivered']) ? $firstTrackingObject['date'] : null],
			    ['tracking' => $trackingObject]
			);

		} catch ( RequestException $e )
	    {
	      throw new CorreiosTrackingException($e->getMessage(), $e->getCode());
	    }

	}

}