<?php

namespace BUBB\Correios\Exceptions;

use Exception;

class ZipcodeException extends Exception
{

	public function __construct($message)
	{
		parent::__construct($message);
	}

}