<?php

namespace Stellar\Vortex\Navigation\Directory\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnScanDirectoryException extends Exception
{
    public function __construct(private readonly string $path)
    {
        parent::__construct(
            "Failed on scan $path.",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
