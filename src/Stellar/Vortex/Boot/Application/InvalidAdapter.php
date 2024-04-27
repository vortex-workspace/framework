<?php

namespace Stellar\Vortex\Boot\Application;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidAdapter extends Exception
{
    public function __construct(public readonly string $class_name)
    {
        parent::__construct(
            "Failed on try register non Adapter class $this->class_name. To be registered the class must be instance of Stellar\Vortex\Adapter",
            ExceptionCode::NON_CATCH_EXCEPTION,
            Level::Critical
        );
    }
}
