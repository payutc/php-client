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

use \Payutc\Client\JsonClient;

/**
 * Auto generate calls client:
 * 
 * $client->myFunc(array("a"=>42)) will remotly call method "myFunc" with 
 * param a=42.
 * 
 */
class AutoJsonClient extends JsonClient
{
	public function __call($name, $arguments)
	{
		if (count($arguments) < 1) {
			return $this->apiCall($name);
		}
		else {
			return $this->apiCall($name, $arguments[0]);
		}
	}
}
