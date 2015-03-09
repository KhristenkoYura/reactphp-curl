<?php

namespace React\Curl;

use \React\Promise\Deferred;

class Result extend \multiCurl\Result {

    /**
     * @return Deferred
     */
    public function shiftDeferred($opts, $params = []) {
        $deferred = $query['params']['__deferred'];
        unset($query['params']['__deferred']);
        return $deferred;
    }
}
