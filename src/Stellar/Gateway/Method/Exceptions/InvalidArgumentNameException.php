<?php

namespace Stellar\Gateway\Method\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidArgumentNameException extends Exception
{
    public function __construct(string $argument_name)
    {
        parent::__construct(
            "Invalid name of argument \"$argument_name\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}