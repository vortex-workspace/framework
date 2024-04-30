<?php

namespace Stellar\Navigation;

use Stellar\Navigation\Path\Exceptions\PathNotFound;

class Path
{
    /**
     * @param string $relative_path
     * @return string
     * @throws PathNotFound
     */
    public static function realPath(string $relative_path): string
    {
        if (($full_path = realpath($relative_path)) === false) {
            throw new PathNotFound($relative_path);
        }

        return $full_path;
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     */
    public static function exist(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            try {
                $path = self::realPath($path);
            } catch (PathNotFound) {
                return false;
            }
        }

        return file_exists($path);
    }
}