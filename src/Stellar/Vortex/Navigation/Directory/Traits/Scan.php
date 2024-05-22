<?php

namespace Stellar\Vortex\Navigation\Directory\Traits;

use Stellar\Vortex\Helpers\ArrayTool;
use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Helpers\Typography\Enum\Typography;
use Stellar\Vortex\Navigation\Directory\Enum\ScanDirSortMode;
use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Vortex\Navigation\Path\Exceptions\TypeNotMatchException;

trait Scan
{
    /**
     * @param string $path
     * @param ScanDirSortMode $mode
     * @param bool $is_real_path
     * @param $context
     * @param bool $return_full_path
     * @param bool $return_key_equals_value
     * @return bool|array
     * @throws TypeNotMatchException
     */
    public static function scan(
        string          $path,
        ScanDirSortMode $mode = ScanDirSortMode::ASC,
        bool            $is_real_path = false,
                        $context = null,
        bool            $return_full_path = false,
        bool            $return_key_equals_value = false
    ): bool|array
    {
        try {
            return self::scanOrFail($path, $mode, $is_real_path, $context, $return_full_path, $return_key_equals_value);
        } catch (PathNotFoundException) {
            return false;
        }
    }

    /**
     * @param string $path
     * @param ScanDirSortMode $mode
     * @param bool $is_real_path
     * @param $context
     * @param bool $return_full_path
     * @param bool $return_key_equals_value
     * @return false|array
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    public static function scanOrFail(
        string          $path,
        ScanDirSortMode $mode = ScanDirSortMode::ASC,
        bool            $is_real_path = false,
                        $context = null,
        bool            $return_full_path = false,
        bool            $return_key_equals_value = false
    ): false|array
    {
        if (!$is_real_path) {
            $path = Path::fullPath(Path::trim($path));
        }

        if (!$top_directories = scandir($path, $mode->value, $context)) {
            throw new TypeNotMatchException($path, 'Directory');
        }

        $return = ArrayTool::deleteValue($top_directories, ['.', '..']);

        if ($return_full_path) {
            $return = self::mountWithFullPath($path, $return);
        }

        if ($return_key_equals_value) {
            return ArrayTool::setKeyEqualValue($return);
        }

        return $return;
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
     * @param ScanDirSortMode $mode
     * @param bool $is_real_path
     * @param $context
     * @param bool $return_full_path
     * @return array
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    public static function recursiveScan(
        string          $path,
        ScanDirSortMode $mode = ScanDirSortMode::ASC,
        bool            $is_real_path = false,
                        $context = null,
        bool            $return_full_path = false
    ): array
    {
        $primary_level = self::scan($path, $mode, $is_real_path, $context, $return_full_path, true);

        foreach ($primary_level as $index => $value) {
            $full_path = $return_full_path ?
                $value :
                StrTool::forceFinishWith($path, Typography::SLASH->value) .
                StrTool::removeIfStartWith($value, Typography::SLASH->value);

            if (Path::isDirectory($full_path, true)) {
                $primary_level[$index] = self::recursiveScan(
                    $full_path,
                    $mode,
                    $return_full_path,
                    $context,
                    $return_full_path
                );
            }
        }

        return $primary_level;
    }
}