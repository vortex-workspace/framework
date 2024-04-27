<?php

namespace Stellar\Vortex\Router\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
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
