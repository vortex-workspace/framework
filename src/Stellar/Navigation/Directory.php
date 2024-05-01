<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Enums\ScanSortMode;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Directory\Exceptions\FailedOnDeleteDirectory;
use Stellar\Navigation\Directory\Exceptions\FailedOnScanDirectory;
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

    /**
     * @param string $directory_path
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteDirectory
     * @throws PathNotFound
     */
    public static function delete(string $directory_path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $directory_path = static::realPath($directory_path);
        }

        if (rmdir($directory_path) === false) {
            throw new FailedOnDeleteDirectory($directory_path);
        }

        return true;
    }

    /**
     * @param string $directory_path
     * @param ScanSortMode $scanSortMode
     * @param bool $is_real_path
     * @param bool $key_as_path
     * @param bool $exclude_parents
     * @return array
     * @throws FailedOnScanDirectory
     * @throws PathNotFound
     */
    public static function scan(
        string       $directory_path,
        ScanSortMode $scanSortMode = ScanSortMode::None,
        bool         $is_real_path = false,
        bool         $key_as_path = false,
        bool         $exclude_parents = true
    ): array
    {
        if (!$is_real_path) {
            $directory_path = static::realPath($directory_path);
        }

        if (!($scan = scandir($directory_path, $scanSortMode->value))) {
            throw new FailedOnScanDirectory($directory_path);
        }

        if ($exclude_parents) {
            array_pop($scan);
            array_pop($scan);
        }

        if ($key_as_path === true) {
            $formated_scan = [];

            foreach ($scan as $path) {
                $full_path = self::realPath(StrTool::forceFinishWith($directory_path, '/') .
                    StrTool::removeIfStartAndFinishWith($path, '/'));

                $formated_scan[$full_path] = $full_path;
            }

            $scan = $formated_scan;
        }

        return $scan;
    }

    /**
     * @param string $directory_path
     * @param ScanSortMode $scanSortMode
     * @param bool $is_real_path
     * @return array
     * @throws FailedOnScanDirectory
     * @throws PathNotFound
     */
    public static function recursiveScan(
        string       $directory_path,
        ScanSortMode $scanSortMode = ScanSortMode::None,
        bool         $is_real_path = false
    ): array
    {
        $scan = self::scan($directory_path, $scanSortMode, $is_real_path, true);
dump($scan);
sleep(2);
        foreach ($scan as $value) {
            if (self::isDirectory($value, true)) {
                dump('Recursive: ' . $value);
                $scan[$value] = self::recursiveScan($value, $scanSortMode, true);
            }
        }

        return $scan;
    }
}