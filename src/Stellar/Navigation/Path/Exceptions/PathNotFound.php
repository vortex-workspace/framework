<?php

namespace Stellar\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class PathNotFound extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Path not found, \"$this->path\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}