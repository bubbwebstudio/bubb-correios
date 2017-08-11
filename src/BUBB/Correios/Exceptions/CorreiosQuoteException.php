<?php

namespace BUBB\Installments;

use Exception;

class InstallmentsException extends Exception
{

	public function __construct($message)
	{
		parent::__construct($message);
	}

}