<?php

namespace Stellar\RouteDriver\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class RouteNotFoundException extends Exception
{
    public function __construct(string $method, string $uri)
    {
        parent::__construct(
            "Route \"$uri\" not found with method $method.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
        );
    }
}
