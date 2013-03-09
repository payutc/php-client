<?php

require_once "jsonclient/JsonClient.class.php";

$c = new \JsonClient\AutoJsonClient("http://localhost/payutc/server", "POSS2WithExceptions");

var_dump($c->getCasUrl());


try {
	var_dump($c->loadPos());
}
catch (\JsonClient\JsonException $e) {
	echo $e.PHP_EOL;
}

try {
	var_dump($c->loadPos(array("ticket"=>42)));
}
catch (\JsonClient\JsonException $e) {
	echo $e.PHP_EOL;
}

try {
	var_dump($c->loadPos(array("ticket"=>42, "service"=>24, "poi_id"=>48)));
}
catch (\JsonClient\JsonException $e) {
	echo $e.PHP_EOL;
}


