<?php

namespace Stellar\Request\Validations\Rules\DateRule\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidDateFormatException extends Exception
{
    public function __construct(string $format, ?Throwable $previous = null)
    {
        parent::__construct(
            "Invalid date format \"$format\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Critical,
            $previous
        );
    }
}