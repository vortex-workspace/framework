<?php

namespace Stellar\Cosmo;

use Closure;
use Stellar\Cosmo\Argument\Enums\ArgumentMode;

abstract class Argument extends Parameter
{
    /**
     * @param string $name
     * @param string|null $description
     * @param ArgumentMode|null $mode
     * @param mixed|null $default
     * @param array|Closure|null $suggested_values
     * @return static
     */
    public static function make(
        string             $name,
        ?string            $description = '',
        ?ArgumentMode      $mode = null,
        mixed              $default = null,
        array|Closure|null $suggested_values = []
    ): static
    {
        return new static($name, $description, $mode, $default, $suggested_values);
    }

    public function toArray(): array
    {
        return [
            $this->name,
            $this->description,
            $this->mode->value,
            $this->default,
            $this->suggested_values,
        ];
    }

    public function setMode(?ArgumentMode $mode): void
    {
        $this->mode = $mode;
    }

    public function __construct(
        public string             $name,
        public ?string            $description,
        public ?ArgumentMode      $mode,
        public mixed              $default,
        public array|Closure|null $suggested_values,
    )
    {
    }
}