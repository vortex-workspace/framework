<?php

namespace Stellar\Vortex\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class FailedOnCopyException extends Exception
{
    public function __construct(
        string     $to,
        string     $from,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed on try copy \"$from\" to \"$to\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}