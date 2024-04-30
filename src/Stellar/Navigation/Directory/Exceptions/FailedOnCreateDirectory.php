<?php

namespace Stellar\Navigation\Directory\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnCreateDirectory extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Fail on create directory, \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}