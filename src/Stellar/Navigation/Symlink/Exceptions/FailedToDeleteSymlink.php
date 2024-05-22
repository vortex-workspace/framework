<?php

namespace Stellar\Navigation\Symlink\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedToDeleteSymlink extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Failed to delete symbolic link \"$path\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error
        );
    }
}