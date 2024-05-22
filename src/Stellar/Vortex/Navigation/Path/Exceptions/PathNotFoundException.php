<?php

namespace Stellar\Vortex\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class PathNotFoundException extends Exception
{
    public function __construct(
        private readonly string $path,
        ?Throwable              $previous = null
    )
    {
        parent::__construct(
            "Path \"$path\" not found.",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error,
            $previous
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}