<?php

namespace Stellar\Gateway\Method\Exceptions;

use Monolog\Level;
use Stellar\Helpers\ArrayTool;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidArgumentTypeException extends Exception
{
    public function __construct(array|string $argument_types, string $provided)
    {
        $argument_types = is_array($argument_types) ?
            ArrayTool::implode($argument_types, ', ') :
            $argument_types;

        parent::__construct(
            "Invalid argument type, expected $argument_types, provided $provided.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}