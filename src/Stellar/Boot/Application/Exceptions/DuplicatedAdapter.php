<?php

namespace Stellar\Boot\Application\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class DuplicatedAdapter extends Exception
{
    public function __construct(public readonly string $class_name)
    {
        parent::__construct(
            "Try register multiple classes with same name \"$this->class_name\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
