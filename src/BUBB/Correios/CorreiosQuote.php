<?php

namespace BUBB\Correios;

use BUBB\Correios\Exceptions\CorreiosQuoteException;

use SoapFault, SoapClient;
use Log;
use Carbon\Carbon;

class CorreiosQuote
{

	protected $originZipcode = null;

	protected $destinyZipcode = null;

	protected $weight = null;

	protected $width = null;

	protected $height = null;

	protected $length = null;

	protected $serviceCodes = null;

	protected $format = null;

	protected $companyCode = null;

	protected $password = null;

	protected $invalidAttributes = null;

	protected $diameter = null;

	protected $maoPropria = false;

	protected $avisoRecebimento = false;

	protected $valorDeclarado = 0;

	protected $correiosServicesCodes = [
		'4014' => ['name' => 'sedex', 'optional_name' => 'Sedex'],
		'40215' => ['name' => 'sedex_10', 'optional_name' => 'Sedex 10'],
		'40045' => ['name' => 'sedex_a_cobrar', 'optional_name' => 'Sedex a cobrar'],
		'4162' => ['name' => 'sedex_contrato_04162', 'optional_name' => 'Sedex'],
		'4316' => ['name' => 'sedex_contrato_04316', 'optional_name' => 'Sedex'],
		'40096' => ['name' => 'sedex_contrato_40096', 'optional_name' => 'Sedex'],
		'40436' => ['name' => 'sedex_contrato_40436', 'optional_name' => 'Sedex'],
		'40444' => ['name' => 'sedex_contrato_40444', 'optional_name' => 'Sedex'],
		'40568' => ['name' => 'sedex_contrato_40568', 'optional_name' => 'Sedex'],
		'40290' => ['name' => 'sedex_hoje', 'optional_name' => 'Sedex hoje'],
		'40096' => ['name' => 'sedex_contrato', 'optional_name' => 'Sedex'],
		'4510' => ['name' => 'pac', 'optional_name' => 'PAC'],
		'41068' => ['name' => 'pac_contrato', 'optional_name' => 'PAC'],
		'4669' => ['name' => 'pac_contrato_04669', 'optional_name' => 'PAC'],
		'4812' => ['name' => 'pac_contrato_04812', 'optional_name' => 'PAC'],
		'41068' => ['name' => 'pac_contrato_41068', 'optional_name' => 'PAC'],
		'41211' => ['name' => 'pac_contrato_41211', 'optional_name' => 'PAC']
	];

	public function setValorDeclarado($value)
	{
		return $this->valorDeclarado = $value;
	}

	public function getValorDeclarado()
	{
		return $this->valorDeclarado;
	}

	public function setAvisoRecebimento($value)
	{
		return $this->avisoRecebimento = $value;
	}

	public function getAvisoRecebimento()
	{
		return $this->avisoRecebimento;
	}

	public function setOriginZipcode($value)
	{
		return $this->originZipcode = $value;
	}

	public function getOriginZipcode()
	{
		return $this->originZipcode;
	}

	public function setDestinyZipcode($value)
	{
		return $this->destinyZipcode = $value;
	}

	public function getDestinyZipcode()
	{
		return $this->destinyZipcode;
	}

	public function setWeight($value)
	{
		return $this->weight = $value;
	}

	public function getWeight()
	{
		return $this->weight;
	}

	public function setMaoPropria($value)
	{
		return $this->maoPropria = $value;
	}

	public function getMaoPropria()
	{
		return $this->maoPropria;
	}

	public function setWidth($value)
	{
		return $this->width = $value;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function setHeight($value)
	{
		return $this->height = $value;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function setLength($value)
	{
		return $this->length = $value;
	}

	public function getLength()
	{
		return $this->length;
	}

	public function setServicesCodes($value)
	{
		return $this->servicesCodes = $value;
	}

	public function getServicesCodes()
	{
		return $this->servicesCodes;
	}

	public function setFormat($value)
	{
		return $this->format = $value;
	}

	public function getFormat()
	{
		return $this->format;
	}

	public function setCompanyCode($value)
	{
		return $this->companyCode = $value;
	}

	public function getCompanyCode()
	{
		return $this->companyCode;
	}

	public function setPassword($value)
	{
		return $this->password = $value;
	}

	public function getPassword($value)
	{
		return $this->password;
	}

	public function setDiameter($value)
	{
		return $this->diameter = $value;
	}

	public function getDiameter($value)
	{
		return $this->diameter;
	}

	public function getCorreiosServicesCodes()
	{
		return $this->correiosServicesCodes;
	}

	/**
	 * getRequiredAttributes
	 * @return array
	 */
	public function getRequiredAttributes()
	{
		return ['originZipcode', 'destinyZipcode', 'weight', 'width', 'height', 'length', 'servicesCodes', 'format'];
	}

	/**
	 * get
	 * Return the Correios quotes
	 * @return array
	 */
	public function get()
	{

		if ( !$this->hasAllRequiredAttributes() )
			throw new CorreiosQuoteException(sprintf('Required attributes: %s', implode($this->getMissingAttributes(), ', ')));

		if ( $this->hasInvalidServiceCodes() )
			throw new CorreiosQuoteException('Invalid service code');

		return $this->response();

	}

	/**
	 * response
	 * @return array
	 */
	private function response()
	{

		$formats = [
			'caixa' => 1,
			'rolo' => 2,
			'envelope' => 3
		];

		if ( !isset($formats[$this->getFormat()]) )
			throw new CorreiosQuoteException('Invalid format. Accepted formats: caixa, rolo and envelope');

		$params = [
			'nCdEmpresa' => is_null($this->getCompanyCode()) ? '' : $this->getCompanyCode(),
			'sDsSenha' => is_null($this->getPassword()) ? '' : $this->getPassword(),
			'nCdServico' => implode(',', $this->getServicesCodes()),
			'sCepOrigem' => preg_replace("/[^0-9]/", '', $this->getOriginZipcode()),
			'sCepDestino' => preg_replace("/[^0-9]/", '', $this->getDestinyZipcode()),
			'nVlPeso' => (string) $this->getWeight(),
			'nCdFormato' => (string) $formats[$this->getFormat()],
			'nVlComprimento' => (string) $this->getLength(),
			'nVlAltura' => (string) $this->getHeight(),
			'nVlLargura' => (string) $this->getWidth(),
			'nVlDiametro' => is_null($this->getDiameter()) ? 0 : $this->getDiameter(),
			'sCdMaoPropria' => $this->getMaoPropria() ? 'S' : 'N',
			'nVlValorDeclarado' => $this->getValorDeclarado(),
			'sCdAvisoRecebimento' => $this->getAvisoRecebimento() ? 'S' : 'N',
			'sDtCalculo' => date('d/m/Y')
		];

		$soapOptions = [
			'trace'              => true,
	        'exceptions'         => true,
	        'compression'        => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
	        'connection_timeout' => 1000
		];

		try
		{

			$soap = new \SoapClient('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL', $soapOptions);
			
			$request = $soap->CalcPrecoPrazoData($params);
			$result = $request->CalcPrecoPrazoDataResult->Servicos->cServico;

			if ( !is_array($result) )
				$result = array($result);

			$quotes = [];

			foreach ( $result as $quote )
			{

				$quote = (array) $quote;

				if ( $quote['MsgErro'] == '' )
				{
					array_push($quotes, [
					  'price' => (float) str_replace(',','.', $quote['Valor']),
					  'delivery_time' => (int) $quote['PrazoEntrega'],
					  'estimate_delivery_date' => Carbon::now()->addWeekdays($quote['PrazoEntrega'])->format('Y-m-d'),
					  'code' => $quote['Codigo'],
					  'service' => $this->getCorreiosServicesCodes()[$quote['Codigo']],
					  'weight' => $this->getWeight()
					]);
				}
				else
				{
					throw new CorreiosQuoteException($quote['MsgErro']);
				}

			}

			return $quotes;

		} catch (SoapFault $e) {

	      set_error_handler('var_dump', 0); // Never called because of empty mask.
	      @trigger_error("");
	      restore_error_handler();

	      throw new CorreiosQuoteException($e->getMessage());

	    }

	}

	/**
	 * hasInvalidServiceCodes
	 * @return boolean
	 */
	private function hasInvalidServiceCodes()
	{
		$has = false;

		foreach ( $this->getServicesCodes() as $key => $code )
		{
			if ( !isset($this->getCorreiosServicesCodes()[$code]) )
				$has = true;
		}

		return $has;

	}

	/**
	 * hasAllRequiredAttributes
	 * @return boolean
	 */
	private function hasAllRequiredAttributes()
	{
		return count($this->getMissingAttributes()) == 0;
	}

	/**
	 * getMissingAttributes
	 * @return array
	 */
	private function getMissingAttributes()
	{

		$missingAttributes = [];

		foreach ( $this->getRequiredAttributes() as $attribute )
		{
			$method = 'get'.ucfirst($attribute);
			if ( is_null($this->$method()) )
				array_push($missingAttributes, $attribute);
		}

		return $missingAttributes;

	}

	

}