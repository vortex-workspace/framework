<?php

namespace Stellar\Cosmo\Command\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class InvalidOutputStyle extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Invalid Output Style class.',
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Error
        );
    }
}