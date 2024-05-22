<?php

namespace Stellar\Storage\StorageDrive\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnMoveFile extends Exception
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            "Failed on try move file from [ $from ] to [ $to ].",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}