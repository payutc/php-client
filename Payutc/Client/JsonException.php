<?php
/**
* payutc
* Copyright (C) 2013-2014 payutc <payutc@assos.utc.fr>
*
* This file is part of payutc
* 
* payutc is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* payutc is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Payutc\Client;

class JsonException extends \Exception
{
	protected $type;
	protected $http_code;
	
	public function __construct($type, $code, $msg = null, $http_code = null)
	{
		parent::__construct($msg, $code);
		$this->type = $type;
		$this->http_code = $http_code;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getHttpCode()
	{
		return $this->http_code;
	}
	
	public function __tostring()
	{
		return "JsonException(type={$this->type}, code={$this->getCode()}, msg={$this->getMessage()}, http_code={$this->http_code})";
	}
}
