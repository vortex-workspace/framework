<?php

namespace Stellar\Boot\ApplicationBuilder\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class MissingEnvironmentFileException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'Missing .env file from project root directory.',
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error,
            $previous
        );
    }
}