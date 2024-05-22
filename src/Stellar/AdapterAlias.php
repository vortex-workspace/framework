<?php

namespace Stellar;

class AdapterAlias
{
    public static function make(string $namespace, string $class_name, string $default_class): static
    {
        return new static($namespace, $class_name, $default_class);
    }

    private function __construct(public string $namespace, public string $class_name, public string $default_class)
    {
    }
}