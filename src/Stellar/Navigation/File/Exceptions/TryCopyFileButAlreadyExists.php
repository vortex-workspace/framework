<?php

namespace Stellar\Navigation\File\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class TryCopyFileButAlreadyExists extends Exception
{
    public function __construct(public readonly string $from, public readonly string $to)
    {
        parent::__construct(
            "Fail on try copy file from \"$this->from\" to \"$this->to\" because the same already exists and not in \"overwrite\" mode.",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}