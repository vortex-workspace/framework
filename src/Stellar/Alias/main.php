<?php

use Stellar\Helpers\Environment;

$base_directory = __DIR__;

require_once $base_directory . '/project_path.php';

function env(string $key, mixed $default = null)
{
    return Environment::get($key, $default);
}