<?php

namespace Stellar\Gateway\Method\Exceptions;

use Monolog\Level;
use ReflectionParameter;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class MissingRequiredArgumentException extends Exception
{
    public function __construct(ReflectionParameter $argument)
    {
        parent::__construct(
            "Missing required argument \"" . $argument->getName() ."\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}