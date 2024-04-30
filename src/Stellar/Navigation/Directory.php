<?php

namespace Stellar\Navigation;

use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

class Directory extends Path
{
    /**
     * @param string $path
     * @param bool $is_real_path
     * @param bool $recursive
     * @param string $permissions
     * @return bool
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     */
    public static function create(
        string $path,
        bool   $is_real_path = false,
        bool   $recursive = false,
        string $permissions = '0777'
    ): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        if (mkdir($path, $permissions, $recursive) === false) {
            throw new FailedOnCreateDirectory($path);
        }

        return true;
    }
}