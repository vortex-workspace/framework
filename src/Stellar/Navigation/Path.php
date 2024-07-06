<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Path\Exceptions\FailedOnRenamePath;
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

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     */
    public static function notExist(string $path, bool $is_real_path = false): bool
    {
        return !self::exist($path, $is_real_path);
    }

    /**
     * @param string $path
     * @param string $final_name
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnRenamePath
     * @throws PathNotFound
     */
    public static function rename(string $path, string $final_name, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        $final_path = StrTool::replace(
            $path,
            basename($path),
            StrTool::removeIfStartAndFinishWith($final_name, '/')
        );

        if (rename($path, $final_path) === false) {
            throw new FailedOnRenamePath($path, $final_name);
        }

        return true;
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws PathNotFound
     */
    public static function isDirectory(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        return is_dir($path);
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws PathNotFound
     */
    public static function isFile(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        return is_file($path);
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws PathNotFound
     */
    public static function isSymlink(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = Symlink::realPath($path);
        }

        return is_link($path);
    }

    /**
     * - Return directory path with correct OS separator.
     * @param array $trace
     * @return string|null
     */
    public static function mountPath(array $trace): ?string
    {
        if (empty($trace)) {
            return null;
        }

        $path = '';

        foreach ($trace as $single) {
            $path .= DIRECTORY_SEPARATOR . StrTool::removeIfStartAndFinishWith($single, ['/', '\\']);
        }

        return $path;
    }
}