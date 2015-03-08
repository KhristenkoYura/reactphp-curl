<?php

namespace React\Curl;


class Exception extends \RuntimeException {
    /**
     * @var \multiCurl\Result
     */
    public $result;

    public function __construct(\multiCurl\Result $result) {
        $this->result = $result;
        parent::__construct($result->getError(), $result->getErrorCode());
    }
}