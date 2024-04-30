<?php

namespace Stellar\Navigation\File\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnRenameFile extends Exception
{
    public function __construct(public readonly string $path, public readonly string $final_filename)
    {
        parent::__construct(
            "Fail on try rename file \"$this->path\" to \"$this->final_filename\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}