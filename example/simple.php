<?php

use \React\EventLoop\Factory;
use \KHR\React\Curl\Curl;
use \KHR\React\Curl\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$cb_ok = function(MCurl\Result $result){
    echo $result->info['url'], PHP_EOL;
    //print_r($result->info);
};

$cb_err = function(Exception $e){
    echo $e->result->info['url'], "\t", $e->getMessage(), PHP_EOL;
};


$loop = Factory::create();
$curl = new Curl($loop);

// Config
$curl->client->setMaxRequest(3);
$curl->client->setSleep(6, 1.0, false); // 6 request in 1 second
$curl->client->setCurlOption([CURLOPT_AUTOREFERER => true, CURLOPT_COOKIE => 'fruit=apple; colour=red']); // default options

// More config $curl->client show https://github.com/KhristenkoYura/multicurl
// endconfig

//get result 
$curl->get('https://graph.facebook.com/http://www.yandex.ru')->then(function($result){
	echo (string) $result, PHP_EOL; // echo $result->body; OR echo $result->getBody();
});

// get json
$curl->get('https://graph.facebook.com/http://google.com')->then(function($result){
	print_r($result->json);
	// print_r($result->getJson(true)); -> print array
});

$curl->get('https://www.google.ru/')->then($cb_ok, $cb_err);
$curl->get('http://www.yandex.ru/', [CURLOPT_REFERER => 'https://google.ru/'])->then($cb_ok, $cb_err);

$curl->get('http://yandex.ru/404.html', [CURLOPT_REFERER => 'https://google.ru/'])->then($cb_ok, $cb_err);



$curl->add([CURLOPT_URL => 'http://www.yandex.ru/'])->then($cb_ok, $cb_err);
$curl->add([CURLOPT_URL => 'https://google.ru/'])->then($cb_ok, $cb_err);
//Run request in method add
$curl->run();

$curl->post('http://www.yandex.ru/post', ['post-key' => 'post-value'])->then($cb_ok, $cb_err);


$loop->run();
