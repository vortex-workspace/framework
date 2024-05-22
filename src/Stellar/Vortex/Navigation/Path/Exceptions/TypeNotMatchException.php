<?php

namespace Stellar\Vortex\Navigation\Path\Exceptions;

use Monolog\Level;
use Stellar\Vortex\Throwable\Exceptions\Contracts\Exception;
use Stellar\Vortex\Throwable\Exceptions\Enum\ExceptionCode;

class TypeNotMatchException extends Exception
{
    public function __construct(string $path, string $type = 'File, Directory or Symlink')
    {
        parent::__construct(
            "Path $path not match, need $type.",
            ExceptionCode::DEVELOPER_EXCEPTION,
            Level::Critical
        );
    }
}
