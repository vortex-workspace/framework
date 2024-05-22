<?php

use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\ProjectPath;

/**
 * @param string|null $additional_path
 * @return string
 * @throws PathNotFound
 */
function root_path(?string $additional_path = null): string
{
    return ProjectPath::rootPath($additional_path);
}

/**
 * @param string|null $additional_path
 * @return string
 * @throws PathNotFound
 */
function vendor_path(?string $additional_path = null): string
{
    return ProjectPath::vendorPath($additional_path);
}

/**
 * @param string|null $additional_path
 * @return string
 * @throws PathNotFound
 */
function framework_path(?string $additional_path = null): string
{
    return ProjectPath::frameworkPath($additional_path);
}

/**
 * @param string|null $additional_path
 * @return string
 * @throws PathNotFound
 */
function storage_path(?string $additional_path = null): string
{
    return ProjectPath::storagePath($additional_path);
}

/**
 * @param string|null $additional_path
 * @return string
 * @throws PathNotFound
 */
function public_path(?string $additional_path = null): string
{
    return ProjectPath::publicPath($additional_path);
}
