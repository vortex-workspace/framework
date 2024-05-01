<?php

namespace Stellar\Navigation\Directory\Traits;

use Stellar\Helpers\ArrayTool;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Enums\ScanSortMode;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

trait Scan
{
    /**
     * @param string $path
     * @param ScanSortMode $mode
     * @param bool $is_real_path
     * @param bool $key_as_path
     * @param bool $return_full_path
     * @param bool $exclude_parents
     * @return bool|array
     * @throws PathNotFound
     */
    public static function scan(
        string       $path,
        ScanSortMode $mode = ScanSortMode::None,
        bool         $is_real_path = false,
        bool         $key_as_path = false,
        bool         $return_full_path = false,
        bool         $exclude_parents = false
    ): bool|array
    {
        if (!$is_real_path) {
            $path = self::realPath($path);
        }

        $directories = scandir($path, $mode->value);

        if ($exclude_parents) {
            $directories = ArrayTool::deleteValue($directories, ['.', '..']);
        }

        if ($return_full_path) {
            $directories = self::mountWithFullPath($path, $directories);
        }

        if ($key_as_path) {
            return ArrayTool::setKeyEqualValue($directories);
        }

        return $directories;
    }

    private static function mountWithFullPath(
        string $directory_path,
        array  $directory_sub_elements
    ): array
    {
        $formatted_array = [];

        foreach ($directory_sub_elements as $index => $directory_element_name) {
            $formatted_array[$index] = "$directory_path/$directory_element_name";
        }

        return $formatted_array;
    }

    /**
     * @param string $path
     * @param ScanSortMode $mode
     * @param bool $is_real_path
     * @param bool $return_full_path
     * @return array
     * @throws PathNotFound
     */
    public static function recursiveScan(
        string       $path,
        ScanSortMode $mode = ScanSortMode::None,
        bool         $is_real_path = false,
        bool         $return_full_path = true,
    ): array
    {
        $directories = self::scan($path, $mode, $is_real_path, true, $return_full_path, true);

        foreach ($directories as $index => $value) {
            $full_path = $return_full_path ? $value :
                StrTool::forceFinishWith($path, '/') . StrTool::removeIfStartWith($value, '/');

            if (self::isDirectory($full_path, true)) {
                $directories[$index] = self::recursiveScan(
                    $full_path,
                    $mode,
                    true,
                    $return_full_path
                );
            }
        }

        return $directories;
    }
}