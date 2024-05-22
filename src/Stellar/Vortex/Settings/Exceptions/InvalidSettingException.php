<?php

namespace Stellar\Vortex\Settings\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
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