<?php

namespace Stellar\Vortex\Navigation\Directory\Traits;

use Stellar\Vortex\Navigation\Directory\Exceptions\FailedOnScanDirectoryException;
use Stellar\Vortex\Navigation\File;
use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnDeleteException;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Vortex\Navigation\Path\Exceptions\TypeNotMatchException;

trait Delete
{
    /**
     * @param string $path
     * @param bool $recursive_delete
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteException
     * @throws FailedOnScanDirectoryException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    public static function delete(string $path, bool $recursive_delete = false, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = Path::fullPath($path);
        }

        if ($recursive_delete) {
            return self::recursiveDelete($path);
        }

        return self::simpleDelete($path);
    }

    /**
     * @param string $path
     * @return bool
     * @throws FailedOnDeleteException
     */
    public static function simpleDelete(string $path): bool
    {
        if (rmdir($path) === false) {
            throw new FailedOnDeleteException($path);
        }

        return true;
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteException
     * @throws FailedOnScanDirectoryException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    public static function recursiveDelete(
        string $path,
        bool   $is_real_path = false
    ): bool
    {
        if (($sub_elements = self::scan($path, is_real_path: $is_real_path, return_full_path: true)) === false) {
            throw new FailedOnScanDirectoryException($path);
        }

        foreach ($sub_elements as $key => $single_element) {
            if (Path::isDirectory($single_element)) {
                $sub_elements[$key] = self::recursiveDelete($single_element, is_real_path: true);
            } else {
                File::delete($single_element, true);
            }
        }

        if (!rmdir(Path::fullPath($path))) {
            throw new FailedOnDeleteException($path);
        }

        return true;
    }
}