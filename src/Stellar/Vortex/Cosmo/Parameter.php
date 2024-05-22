<?php

namespace Stellar\Vortex\Cosmo;

use Closure;
use Stellar\Vortex\Cosmo\Argument\Enums\ArgumentMode;

abstract class Parameter
{
    public string $name;
    public ?string $description;
    public mixed $default;
    public array|Closure|null $suggested_values;

    abstract public function toArray(): array;

    public function setDefault(mixed $default): static
    {
        $this->default = $default;

        return $this;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setSuggestedValues(array|Closure $suggested_values): static
    {
        $this->suggested_values = $suggested_values;

        return $this;
    }
}