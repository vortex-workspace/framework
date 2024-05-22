<?php

namespace Stellar\Storage\StorageDrive\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class DrivePartitionIsDisabled extends Exception
{
    public function __construct(public readonly string $drive, public readonly string $partition)
    {
        parent::__construct(
            "Blocked on try access not enabled \"$this->partition\" partition from drive \"$this->drive\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}