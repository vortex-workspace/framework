<?php

namespace Stellar\Vortex\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class PathAlreadyExistException extends Exception
{
    public function __construct(
        string     $path,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Path \"$path\" already exist.",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}