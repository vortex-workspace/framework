<?php

namespace Stellar\Vortex\Route\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class RouteNameAlreadyInUse extends Exception
{
    public function __construct(string $route, ?Throwable $previous = null)
    {
        parent::__construct(
            "Try set multiple routes with same name \"$route\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}
