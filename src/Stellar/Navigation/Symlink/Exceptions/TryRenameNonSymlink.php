<?php

namespace Stellar\Navigation\Symlink\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class TryRenameNonSymlink extends Exception
{
    public function __construct(public readonly string $path)
    {
        parent::__construct(
            "Failed to rename symbolic link from \"$path\" because not is a symlink.",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error
        );
    }
}