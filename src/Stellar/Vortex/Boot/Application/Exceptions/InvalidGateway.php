<?php

namespace Stellar\Vortex\Boot\Application\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidGateway extends Exception
{
    public function __construct(public readonly string $class_name)
    {
        parent::__construct(
            "Failed on try register non Gateway class $this->class_name. To be registered the class must be instance of Stellar\Core\Contracts\Boot\GatewayInterface",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
