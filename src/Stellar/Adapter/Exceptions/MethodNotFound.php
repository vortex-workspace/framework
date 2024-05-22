<?php

namespace Stellar\Adapter\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class MethodNotFound extends Exception
{
    public function __construct(public readonly string $method_name)
    {
        parent::__construct(
            "Called method not found \"$this->method_name\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
