<?php

namespace Stellar\Navigation\File\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnMoveFile extends Exception
{
    public function __construct(public readonly string $from, public readonly string $to)
    {
        parent::__construct(
            "Fail on try move file from \"$this->from\" to \"$this->to\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}