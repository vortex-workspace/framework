<?php

namespace Stellar\Navigation\Pointer\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedToOpenFilePointer extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Failed to open pointer on file \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}