<?php

namespace Stellar\Gateway;

use Closure;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use Stellar\Adapter;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentNameException;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentNumberException;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentTypeException;
use Stellar\Gateway\Method\Exceptions\MissingRequiredArgumentException;
use Stellar\Helpers\ArrayTool;
use Stellar\Helpers\StrTool;

class Method
{
    private array $required_arguments = [];
    /** @var ReflectionParameter[] */
    private array $arguments = [];
    private ReflectionFunction $callableReflection;

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
     * @param Closure $execution
     * @throws ReflectionException
     */
    private function __construct(
        public string  $name,
        public Closure $execution,
    )
    {
        $this->callableReflection = new ReflectionFunction($this->execution);
        $this->arguments = $this->callableReflection->getParameters();
        $arguments = [];
        unset($this->arguments[0]);

        $this->arguments = array_values($this->arguments);

        foreach ($this->arguments as $argument) {
            $arguments[] = $argument;

            if (!$argument->isDefaultValueAvailable()) {
                $this->required_arguments[] = $argument;
            }
        }

        $this->arguments = $arguments;
    }

    /**
     * @param array $provided_arguments
     * @return array
     * @throws InvalidArgumentNameException
     * @throws InvalidArgumentNumberException
     * @throws InvalidArgumentTypeException
     * @throws MissingRequiredArgumentException
     * @throws ReflectionException
     */
    public function checkArguments(array $provided_arguments): array
    {
        if (($provided_arguments_count = count($provided_arguments)) < count($this->required_arguments)) {
            throw new InvalidArgumentNumberException(
                count($this->required_arguments),
                count($this->arguments),
                $provided_arguments_count
            );
        }

        $non_checked_arguments = $this->arguments;

        foreach ($provided_arguments as $index => $argument) {
            if (is_string($index)) {
                $this->checkArgument($this->getArgumentByName($index), $argument);
                $non_checked_arguments = $this->unsetArgumentByName($index, $non_checked_arguments);

                continue;
            }

            $this->checkArgument(ArrayTool::first($non_checked_arguments), $argument);
            array_shift($non_checked_arguments);
        }

        if (($argument = $this->hasNonSetRequiredArguments($non_checked_arguments)) !== false) {
            throw new MissingRequiredArgumentException($argument);
        }

        if (!empty($non_checked_arguments)) {
            foreach ($non_checked_arguments as $argument) {
                $provided_arguments[$argument->getName()] = $argument->getDefaultValue();
            }
        }

        return $provided_arguments;
    }

    /**
     * @param ReflectionParameter $argument
     * @param mixed $value
     * @return void
     * @throws InvalidArgumentTypeException
     */
    private function checkArgument(ReflectionParameter $argument, mixed $value): void
    {
        $type = gettype($value);

        if ($type === 'object') {
            if ($this->argumentHasType($argument, 'object') ||
                $this->argumentHasType($argument, $class = get_class($value))) {
                return;
            }

            throw new InvalidArgumentTypeException($argument->getType(), $class);
        }

        if (!$this->argumentHasType($argument, $type)) {
            throw new InvalidArgumentTypeException($argument->getType(), $type);
        }
    }

    /**
     * @param array $arguments
     * @param Adapter|string|null $adapter
     * @return mixed
     * @throws InvalidArgumentNameException
     * @throws InvalidArgumentNumberException
     * @throws InvalidArgumentTypeException
     * @throws MissingRequiredArgumentException
     * @throws ReflectionException
     */
    public function execute(array $arguments, null|Adapter|string $adapter): mixed
    {
        $callable = $this->execution;
        $parameters = $this->checkArguments($arguments);

        if ($adapter !== null) {

            array_unshift($parameters, is_string($adapter) ? new $adapter : $adapter);
            ArrayTool::sortRegularlyByKey($parameters);
        }

        return $callable(...$parameters);
    }

    /**
     * @param string $name
     * @return ReflectionParameter
     * @throws InvalidArgumentNameException
     */
    private function getArgumentByName(string $name): ReflectionParameter
    {
        foreach ($this->arguments as $argument) {
            if ($argument->getName() === $name) {
                return $argument;
            }
        }

        throw new InvalidArgumentNameException($name);
    }

    /**
     * @param ReflectionParameter[] $non_used_arguments
     * @return bool|Argument
     */
    private function hasNonSetRequiredArguments(array $non_used_arguments): bool|ReflectionParameter
    {
        foreach ($non_used_arguments as $argument) {
            if (!$argument->isDefaultValueAvailable()) {
                return $argument;
            }
        }

        return false;
    }

    private function unsetArgumentByName(string $argument_name, array $arguments): array
    {
        foreach ($arguments as $index => $argument) {
            if ($argument->name === $argument_name) {
                unset($arguments[$index]);
            }
        }

        return $arguments;
    }

    private function argumentHasType(ReflectionParameter $argument, string $type): bool
    {
        $types = [$argument->getType()];

        if (StrTool::contains($types[0], '|')) {
            $types = explode('|', $argument->getType());
        }

        return in_array($type, $types);
    }

    public function getCallableReflection(): ReflectionFunction
    {
        return $this->callableReflection;
    }
}