<?php

namespace Stellar\Vortex\Navigation\Helpers;

use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;

class Path
{
    /**
     * @param string $file
     * @param bool $is_real_path
     * @return bool
     */
    public static function isFile(string $file, bool $is_real_path = false): bool
    {
        return $is_real_path ? is_file($file) : is_file(self::fullPath($file));
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws PathNotFoundException
     */
    public static function isDirectory(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = self::fullPath($path);
        }

        return is_dir($path);
    }

    public static function isSymlink()
    {

    }

    /**
     * @param string $relative_path
     * @return string
     * @throws PathNotFoundException
     */
    public static function fullPath(string $relative_path): string
    {
        if (!($full_path = realpath(StrTool::removeIfStartWith($relative_path, ['/', '\\', './', '.\\'])))) {
            throw new PathNotFoundException($relative_path);
        }

        return $full_path;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function trim(string $path): string
    {
        return StrTool::removeIfStartAndFinishWith($path, ['/', '\\', './', '.\\']);
    }
}