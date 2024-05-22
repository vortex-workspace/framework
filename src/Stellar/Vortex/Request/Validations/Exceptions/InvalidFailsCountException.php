<?php

namespace Stellar\Vortex\Request\Validations\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidFailsCountException extends Exception
{
    public function __construct(int $fail_limit_count, ?Throwable $previous = null)
    {
        parent::__construct(
            "Fails limit count must be grater than 0, \"$fail_limit_count\" provided.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Critical,
            $previous
        );
    }
}