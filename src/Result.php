<?php

namespace React\Curl;

use \React\Promise\Deferred;

class Result extends \multiCurl\Result {

    /**
     * @return Deferred
     */
    public function shiftDeferred() {
        $deferred = $this->query['params']['__deferred'];
        unset($this->query['params']['__deferred']);
        return $deferred;
    }
}
