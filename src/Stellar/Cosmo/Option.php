<?php

namespace Stellar\Cosmo;

use Closure;
use Stellar\Cosmo\Option\Enums\OptionMode;

abstract class Option extends Parameter
{
    /**
     * @param string $name
     * @param string|array|null $shortcuts
     * @param string|null $description
     * @param OptionMode|null $mode
     * @param mixed|null $default
     * @param array|Closure|null $suggested_values
     * @return static
     */
    public static function make(
        string             $name,
        null|string|array  $shortcuts = null,
        ?string            $description = '',
        ?OptionMode        $mode = null,
        mixed              $default = null,
        array|Closure|null $suggested_values = []
    ): static
    {
        return new static($name, $shortcuts, $description, $mode, $default, $suggested_values);
    }

    public function toArray(): array
    {
        return [
            $this->name,
            $this->shortcuts,
            $this->description,
            $this->mode->value,
            $this->default,
            $this->suggested_values,
        ];
    }
    public function setMode(?OptionMode $mode): void
    {
        $this->mode = $mode;
    }

    public function setShortcuts(array|string $shortcuts): static
    {
        $this->shortcuts = $shortcuts;

        return $this;
    }

    public function __construct(
        public string             $name,
        public null|string|array  $shortcuts,
        public ?string            $description,
        public ?OptionMode        $mode,
        public mixed              $default,
        public array|Closure|null $suggested_values,
    )
    {
    }
}