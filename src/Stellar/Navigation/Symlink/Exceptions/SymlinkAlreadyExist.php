<?php

namespace Stellar\Navigation\Symlink\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class SymlinkAlreadyExist extends Exception
{
    public function __construct(public readonly string $from, public readonly string $to)
    {
        parent::__construct(
            "Failed to create symbolic link from \"$from\" in \"$to\" because already exist and not in force mode.",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error
        );
    }
}