<?php

namespace Stellar\Storage\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class DriveNotDefined extends Exception
{
    public function __construct(public readonly string $drive)
    {
        parent::__construct(
            "Drive \"$this->drive\" not defined in settings.",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}