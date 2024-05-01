<?php

namespace Stellar\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnRenamePath extends Exception
{
    public function __construct(public readonly string $path, public readonly string $final_name)
    {
        parent::__construct(
            "Fail on try rename \"$this->path\" to \"$this->final_name\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}