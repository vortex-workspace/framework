<?php

namespace Stellar\Gateway;

use Closure;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentNameException;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentNumberException;
use Stellar\Gateway\Method\Exceptions\InvalidArgumentTypeException;
use Stellar\Gateway\Method\Exceptions\MissingRequiredArgumentException;
use Stellar\Helpers\ArrayTool;

class Method
{
    private array $required_arguments = [];

    /**
     * @param string $name
     * @param Argument[] $arguments
     * @param callable $execution
     * @return static
     */
    public static function make(string $name, callable $execution, array $arguments = []): static
    {
        return new static($name, $arguments, $execution);
    }

    /**
     * @param string $name
     * @param Argument[] $arguments
     * @param Closure $execution
     */
    private function __construct(
        public string  $name,
        public array   $arguments,
        public Closure $execution
    )
    {
        $arguments = [];

        foreach ($this->arguments as $argument) {
            $arguments[] = $argument;

            if (!isset($argument->default)) {
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
                unset($non_checked_arguments[$argument->name]);

                continue;
            }

            $this->checkArgument(ArrayTool::first($non_checked_arguments), $argument);
            array_shift($non_checked_arguments);
        }

        if (($argument = $this->hasNonSetRequiredArguments($non_checked_arguments)) !== false) {
            throw new MissingRequiredArgumentException($argument);
        }

        if (($provided_arguments_count = count($provided_arguments)) !== ($arguments_count = count($this->arguments))) {
            for ($i = $provided_arguments_count; $i < $arguments_count; $i++) {
                $provided_arguments[] = $this->arguments[$i]->default;
            }
        }

        return $provided_arguments;
    }

    /**
     * @param Argument $argument
     * @param mixed $value
     * @return void
     * @throws InvalidArgumentTypeException
     */
    private function checkArgument(Argument $argument, mixed $value): void
    {
        $type = gettype($value);

        if ($type === 'object') {
            if ($argument->hasType('object') || $argument->hasType($class = get_class($value))) {
                return;
            }

            throw new InvalidArgumentTypeException($argument->type, $class);
        }

        if (!$argument->hasType($type)) {
            throw new InvalidArgumentTypeException($argument->type, $type);
        }
    }

    /**
     * @param array $arguments
     * @return mixed
     * @throws InvalidArgumentNameException
     * @throws InvalidArgumentNumberException
     * @throws InvalidArgumentTypeException
     * @throws MissingRequiredArgumentException
     */
    public function execute(array $arguments): mixed
    {
        $callable = $this->execution;

        return $callable(...$this->checkArguments($arguments));
    }

    /**
     * @param string $name
     * @return Argument
     * @throws InvalidArgumentNameException
     */
    private function getArgumentByName(string $name): Argument
    {
        foreach ($this->arguments as $argument) {
            if ($argument->name === $name) {
                return $argument;
            }
        }

        throw new InvalidArgumentNameException($name);
    }

    private function hasNonSetRequiredArguments(array $non_used_arguments): bool|Argument
    {
        foreach ($non_used_arguments as $argument) {
            if (!isset($argument->default)) {
                return $argument;
            }
        }

        return false;
    }
}