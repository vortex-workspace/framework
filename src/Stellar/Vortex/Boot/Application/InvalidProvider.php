<?php

namespace Stellar\Vortex\Boot\Application;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidProvider extends Exception
{
    public function __construct(public readonly string $class_name)
    {
        parent::__construct(
            "Failed on try register non Provider class $this->class_name. To be registered the class must be instance of Stellar\Vortex\Provider",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
