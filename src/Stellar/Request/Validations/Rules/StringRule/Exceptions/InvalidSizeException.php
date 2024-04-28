<?php

namespace Stellar\Request\Validations\Rules\StringRule\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidSizeException extends Exception
{
    public function __construct(int $size, ?Throwable $previous = null)
    {
        parent::__construct(
            "The string size rule value must be greater than 0, $size provided.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Critical,
            $previous
        );
    }
}