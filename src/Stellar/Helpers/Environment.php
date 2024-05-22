<?php

namespace Stellar\Helpers;

class Environment
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}
