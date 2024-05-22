<?php

namespace Stellar\Navigation\File\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnDeleteFile extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Fail on try delete file, \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}