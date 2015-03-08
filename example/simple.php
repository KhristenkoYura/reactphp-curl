<?php

use \React\EventLoop\Factory;
use \React\Curl\Client;
use \React\Curl\Exception;

require_once __DIR__ . '/../vendor/autoload.php';


$loop = Factory::create();
$client = new Client($loop);

$cb_ok = function(multiCurl\Result $result){
    echo $result->info['url'], PHP_EOL;
    //print_r($result->info);
};

$cb_err = function(Exception $e){
    echo $e->result->info['url'], "\t", $e->getMessage(), PHP_EOL;
};


$client->get('https://www.google.ru/')->then($cb_ok, $cb_err);
$client->get('http://www.yandex.ru/', [CURLOPT_REFERER => 'https://google.ru/'])->then($cb_ok, $cb_err);

$client->get('http://yandex.ru/404.html', [CURLOPT_REFERER => 'https://google.ru/'])->then($cb_ok, $cb_err);



$client->add([CURLOPT_URL => 'http://www.yandex.ru/'])->then($cb_ok, $cb_err);
$client->add([CURLOPT_URL => 'https://google.ru/'])->then($cb_ok, $cb_err);
//Run request in method add
$client->run();

$client->post('http://www.yandex.ru/post', ['post-key' => 'post-value'])->then($cb_ok, $cb_err);


$loop->run();
