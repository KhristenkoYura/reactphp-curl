<?php

namespace React\Curl;

use \React\Promise\Deferred;
use \React\Promise\Promise;

class Client {

    /**
     * @var \React\EventLoop\LoopInterface
     */
    public $loop;

    public $wait_loop = false;


    /**
     * @var \multiCurl\Client
     */
    public $client;

    /**
     * Timeout: check curl resource
     * @var float
     */
    public $timeout = 0.01;



    public function __construct($loop) {
        $this->loop = $loop;
        $this->client = new \multiCurl\Client();
        $this->client->isSelect(false);
    }

    /**
     * @param $url
     * @param array $opts
     * @return Promise
     */
    public function get($url, $opts = array()) {
        $opts[CURLOPT_URL] = $url;
        $promise = $this->add($opts);
        $this->run();
        return $promise;
    }

    /**
     * @param $url
     * @param array $data
     * @param array $opts
     * @return Promise
     */
    public function post($url, $data = array(), $opts = array()) {
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = $data;
        return $this->get($url, $opts);
    }

    /**
     * @param $opts
     * @param array $params
     * @return Promise
     */
    public function add($opts, $params = []) {
        $params['deferred'] = $deferred = new Deferred();
        $this->client->add($opts, $params);
        return $deferred->promise();
    }

    public function run() {
        $client = $this->client;
        $client->run();

        while($client->has()) {
            $result = $client->next();
            /**
             * @var Deferred $deferred
             */
            $deferred = $result->getParams()['deferred'];

            if (!$result->hasError()) {
                $deferred->resolve($result);
            } else {
                $deferred->reject(new Exception($result));
            }
        }

        if (!$this->wait_loop) {
            $this->wait_loop = true;
            $that = $this;
            $this->loop->addTimer($this->timeout, function() use($that){
                $that->wait_loop = false;
                if ($that->client->run() || $that->client->has()) {
                    $this->loop->addTimer($this->timeout, [$that, 'run']);
                }
            });
        }
    }
}