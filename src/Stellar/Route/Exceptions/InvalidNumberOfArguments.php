<?php

namespace Stellar\Route\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidNumberOfArguments extends Exception
{
    public function __construct(string $route, ?Throwable $previous = null)
    {
        parent::__construct(
            "The route $route must be an array with controller and method, or a callable.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}
