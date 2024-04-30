<?php

namespace Stellar\Navigation\Pointer\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class TryCloseNonOpenedPointer extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Try close non opened pointer for file \"$path\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error
        );
    }
}