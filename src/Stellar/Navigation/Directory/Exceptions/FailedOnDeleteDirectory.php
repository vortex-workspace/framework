<?php

namespace Stellar\Navigation\Directory\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnDeleteDirectory extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Fail on try delete directory, check if directory is empty or set force mode, \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}