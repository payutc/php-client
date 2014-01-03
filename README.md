php-client
==========

A PHP client for the [payutc server](http://github.com/payutc/server).

Install
-------

If you are not using the Composer dependency manager in your project, download it :

    $ curl -sS https://getcomposer.org/installer | php

Then, create (or edit) the `composer.json` file :

    {
        "require": {
            "payutc/php-client": "*"
        }
    }

Now, run Composer to download the dependency :

    $ php composer.phar install

Usage
-----

Here is an example of using the `AutoJsonClient`, which allows you to call any method dynamically.

    <?php
    
    require_once "vendor/autoload.php";
    
    use \Payutc\Client\AutoJsonClient;
    use \Payutc\Client\JsonException;
    
    $c = new AutoJsonClient("http://localhost/payutc/server/", "MYACCOUNT");
    
    $result = $c->getCasUrl();
    var_dump($result);
    
    try {
    	$result = $c->loginCas(array(
            "ticket" => "CAS-TICKET-42",
            "service" => "http://localhost/payutc/casper/"
        ));
        var_dump($result);
    }
    catch (JsonException $e) {
    	echo $e.PHP_EOL;
    }
    
