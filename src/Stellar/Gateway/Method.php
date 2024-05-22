<?php

namespace Stellar\Gateway;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class Method
{
    private ReflectionFunction $reflectionFunction;

    /**
     * @param string $name
     * @param callable $callable
     * @return static
     * @throws ReflectionException
     */
    public static function make(string $name, callable $callable): static
    {
        return new static($name, $callable);
    }

    /**
     * @param string $name
     * @param Closure $callable
     * @throws ReflectionException
     */
    private function __construct(
        public string  $name,
        public Closure $callable,
    )
    {
        $this->reflectionFunction = new ReflectionFunction($this->callable);
    }

    public function getCallableReflection(): ReflectionFunction
    {
        return $this->reflectionFunction;
    }
}