<?php

namespace Stellar\Throwable\Exceptions\Contracts;

use Exception as PhpException;
use Monolog\Level;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

abstract class Exception extends PhpException
{
    public function __construct(
        string        $message,
        ExceptionCode $code,
        Level         $level = Level::Debug,
        ?Throwable    $previous = null
    )
    {
        parent::__construct($message, $code->value, $previous);
    }
}