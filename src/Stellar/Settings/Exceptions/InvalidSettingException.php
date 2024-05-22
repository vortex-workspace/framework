<?php

namespace Stellar\Settings\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidSettingException extends Exception
{
    public function __construct(
        string     $setting,
        ?Throwable $previous = null
    )
    {
        parent::__construct(
            "Failed on try get setting \"$setting\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}