<?php

namespace Stellar\Navigation\Stream\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedToWriteFromStream extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Failed to write from stream on file \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}