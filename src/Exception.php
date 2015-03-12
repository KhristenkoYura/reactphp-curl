<?php

namespace KHR\React\Curl;


class Exception extends \RuntimeException {
    /**
     * @var \MCurl\Result
     */
    public $result;

    public function __construct(\MCurl\Result $result) {
        $this->result = $result;
        parent::__construct($result->getError(), $result->getErrorCode());
    }
}