<?php

### Testscript for CURLlib
#require_once(__DIR__ . '/CURL.php');
require_once(__DIR__.'/../vendor/autoload.php');

use AsgeirSH\CURLlib\CURL;

#$curl = new CURL('http://google.com/');
#$result = $curl->exec();

// Test GET
echo 'Test GET'."\n";
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts/1')
	->setTimeout(5)
	->addHeader("X-Auth-Key:xxxxxx")
	->addHeader("X-Auth-Email:example@example.com")
	->exec();
var_dump($result);

// Test other stuff
echo 'Test some other stuff'."\n";
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts/1')
    ->setTimeout(5)
    ->addHeader("X-Auth-Key:xxxxxx")
    ->addHeader("X-Auth-Email:example@example.com")
    ->setPort(80)
    ->exec();
var_dump($result);

// Test POST
echo 'Test POST'."\n";
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts')
    ->setMethod('POST')
    ->setData(array('title' => 'foo', 'body' => 'bar', 'userId' => 1))
    ->exec();
var_dump($result);