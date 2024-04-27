<?php

namespace Stellar\Vortex\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class FailedOnDeleteException extends Exception
{
    public function __construct(
        string     $path,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed on try delete \"$path\"",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}