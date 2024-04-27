<?php

namespace Stellar\Vortex\Facades\Log\Exceptions;

use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidLogHandler extends Exception
{
    public function __construct(?string $origin_class = null, ?string $class_method = null, ?Throwable $previous = null)
    {
        parent::__construct(
            'Logs handlers settings have values of a different type than LogHandler in "log.handlers".',
            ExceptionCode::DEVELOPER_EXCEPTION,
            $origin_class,
            $class_method,
            $previous
        );
    }
}