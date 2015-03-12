<?php

namespace KHR\React\Curl;

use \React\Promise\Deferred;

class Result extends \MCurl\Result {

    /**
     * @return Deferred
     */
    public function shiftDeferred() {
        $deferred = $this->query['params']['__deferred'];
        unset($this->query['params']['__deferred']);
        return $deferred;
    }
}