<?php

namespace Stellar\Gateway\Method\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidArgumentNumberException extends Exception
{
    public function __construct(int $expected_arguments, int $total_arguments, int $provided_arguments)
    {
        parent::__construct(
            "Invalid number of arguments, provided $provided_arguments, expected $expected_arguments required arguments from total of $total_arguments.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}