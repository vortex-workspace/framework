<?php

namespace Stellar\Router\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class FailedOnTryAddRoute extends Exception
{
    public function __construct(string $route, ?Throwable $previous = null)
    {
        parent::__construct(
            "The route $route can't be added because this action is disabled in the moment.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}
