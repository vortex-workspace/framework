<?php

namespace Stellar\Storage\StorageDrive\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnGetMimeType extends Exception
{
    public function __construct(public readonly string $path, string $message)
    {
        parent::__construct(
            "Failed on try get mime type from \"$this->path\". $message",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}