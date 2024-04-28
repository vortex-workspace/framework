<?php

namespace Stellar\Router\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class PrefixIsEnabledButNotFound extends Exception
{
    public function __construct(string $group, ?Throwable $previous = null)
    {
        parent::__construct(
            "The prefix for route group $group is enabled but not set in settings/routes.php custom_route_files.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}
