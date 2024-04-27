<?php

namespace Stellar\Vortex\Throwable\Exceptions\Generics;

use Exception;
use Throwable;

class BadMethodCallException extends Exception
{
    private readonly string $class;
    private readonly string $property;
    public function __construct(string $method, string $class, ?Throwable $previous = null)
    {
        parent::__construct("Bad call to invalid method \"$method\" from class \"$class\".");

        $this->class = $class;
        $this->property = $method;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}