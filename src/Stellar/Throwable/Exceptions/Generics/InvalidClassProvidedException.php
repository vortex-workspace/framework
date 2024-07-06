<?php

namespace Stellar\Throwable\Exceptions\Generics;

use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

class InvalidClassProvidedException extends Exception
{
    public function __construct(string $provided_class, string $expected_class, ?Throwable $previous = null)
    {
        parent::__construct(
            "Invalid class entry, expected \"$expected_class\", provided \"$provided_class\".",
            ExceptionCode::DEVELOPER_EXCEPTION,
            previous: $previous
        );
    }
}