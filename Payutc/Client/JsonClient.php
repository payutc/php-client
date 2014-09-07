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

use \Payutc\Client\JsonException;

/**
 * Basic client to do POST and GET
 */
class JsonClient
{
	protected $url;
	protected $useragent;
	protected $curl_settings;
    protected $result_encoded;
	


	public function __construct($url, $service, $curl_settings = array(), $useragent = "Payutc Json PHP Client", $cookies = array())
	{
		$this->url = $url . '/' . $service . '/';
		$this->useragent = $useragent;
		$this->cookies = $cookies;
		$this->curl_settings = $curl_settings +
			array(
				CURLOPT_USERAGENT => $this->useragent,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_CAINFO => __DIR__ . "/../../TERENA_SSL_CA.pem",
                CURLOPT_HEADERFUNCTION => array($this, 'readHeader'),
			);
	}

    public function readHeader($ch, $header)
    {
        preg_match('/^Set-Cookie: (.*?)=(.*?);/m', $header, $capturedArgs);
        if (array_key_exists(1, $capturedArgs) and array_key_exists(2, $capturedArgs)) {
            $this->cookies[$capturedArgs[1]] = $capturedArgs[1] . '=' . $capturedArgs[2];
        }
        return strlen($header);
    }

    public function getRawContent()
    {
        return $this->result_encoded;
    }

	/**
	 * @param string $func la fonction du service à appeller
	 * @param array $params un array key=>value des paramètres. default: array()
	 * @param string $method le méthode à utiliser (GET ou POST). default: 'POST'
	 */
	public function apiCall($func, array $params = array(), $method = 'POST')
	{
		// Construction de la chaîne de paramètres
		$paramstring = "";
		if (!empty($params)) {
			foreach ($params as $key => $param) {
				$paramstring .= $key . "=" . $param . "&";
			}
			// On supprimer le dernier &
			$paramstring = substr($paramstring, 0, -1);
		}
		
		// Réglages de cURL
		$settings = $this->curl_settings;
		$settings[CURLOPT_CUSTOMREQUEST] = $method;
        if($this->cookies) {
            $settings[CURLOPT_COOKIE] = implode(';', array_values($this->cookies));
        }
		
		// Construction de l'URL et des postfields
		if($method == "GET"){
			$url = $this->url . $func . "?" . $paramstring;
		}
		else {
			$url = $this->url . $func;
			$settings[CURLOPT_POSTFIELDS] = $params;
		}
		
		// Initialisation de cURL
		$ch = curl_init($url);
		curl_setopt_array($ch, $settings);

		// Éxécution de la requête
		$this->result_encoded = curl_exec($ch);
		$result = json_decode($this->result_encoded);

		// Si erreur d'appel de cURL
		if (curl_errno($ch) != 0) {
			throw new JsonException("Unknown", 503, "Erreur d'appel de cURL");
		}
		// Si erreur on throw une exception
		if (isset($result->error)) {
			$err = $result->error;
			$type = isset($err->type) ? $err->type : "UnknownType";
			$code = isset($err->code) ? $err->code : "42";
			$message = isset($err->message) ? $err->message : "";
			throw new JsonException($type, $code, $message, curl_getinfo($ch, CURLINFO_HTTP_CODE));
		} else if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
			throw new JsonException("InvalidHTTPCode", 37, "La page n'a pas renvoye un code 200.", curl_getinfo($ch, CURLINFO_HTTP_CODE));
		} else { // Sinon, on renvoie le résultat
			return $result;
		}
	}
}
