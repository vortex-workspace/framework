<?php

namespace Stellar\Throwable\Exceptions\Generics;

use Monolog\Level;
use ReflectionParameter;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class MissingRequiredArgumentException extends Exception
{
    public function __construct(ReflectionParameter|string $argument)
    {
        parent::__construct(
            "Missing required argument \"" . is_string($argument) ? $argument : $argument->getName() ."\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}