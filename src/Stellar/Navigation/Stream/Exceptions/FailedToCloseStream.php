<?php

namespace Stellar\Navigation\Stream\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedToCloseStream extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Failed to close stream for file \"$path\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error
        );
    }
}