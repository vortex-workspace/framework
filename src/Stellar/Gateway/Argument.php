<?php

namespace Stellar\Gateway;

use Stellar\Gateway\Method\Exceptions\InvalidArgumentTypeException;

readonly class Argument
{
    public mixed $default;

    public static function make(string $name, array|string $type): static
    {
        return new static($name, $type);
    }

    public function __construct(
        public string       $name,
        public array|string $type
    )
    {
    }

    /**
     * @param mixed $default
     * @return $this
     * @throws InvalidArgumentTypeException
     */
    public function setDefault(mixed $default): static
    {
        $type = gettype($default);

        if ($type === 'object') {
            if ($this->hasType('object') || $this->hasType($class = get_class($default))) {
                $this->default = $default;

                return $this;
            }

            throw new InvalidArgumentTypeException($this->type, $class);
        }

        if (!$this->hasType($type)) {
            throw new InvalidArgumentTypeException($this->type, $type);
        }

        $this->default = $default;

        return $this;
    }

    public function hasType(string $name): bool
    {
        return is_array($this->type) ? in_array($name, $this->type) : $this->type === $name;
    }
}