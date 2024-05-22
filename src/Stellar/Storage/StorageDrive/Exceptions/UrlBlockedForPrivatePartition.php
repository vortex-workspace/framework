<?php

namespace Stellar\Storage\StorageDrive\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class UrlBlockedForPrivatePartition extends Exception
{
    public function __construct(public readonly string $drive)
    {
        parent::__construct(
            "Cannot mount url from private partition inside drive \"$this->drive\".",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}