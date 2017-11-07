<?php

namespace KIPR\Exceptions;

class InvalidResultException extends \Exception
{
    public function __construct($message) {
        parent::__construct($message);
    }
}
