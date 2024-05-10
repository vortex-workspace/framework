<?php

namespace Stellar\Gateway\Method\Exceptions;

use Monolog\Level;
use Stellar\Gateway\Argument;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class MissingRequiredArgumentException extends Exception
{
    public function __construct(Argument $argument)
    {
        parent::__construct(
            "Missing required argument \"$argument->name\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}