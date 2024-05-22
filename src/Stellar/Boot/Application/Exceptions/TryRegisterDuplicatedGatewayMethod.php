<?php

namespace Stellar\Boot\Application\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class TryRegisterDuplicatedGatewayMethod extends Exception
{
    public function __construct(public readonly string $method_name)
    {
        parent::__construct(
            "Failed on try register already exist method \"$this->method_name\".",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
