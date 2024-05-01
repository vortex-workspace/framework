<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Directory\Exceptions\FailedOnDeleteDirectory;
use Stellar\Navigation\Directory\Traits\Scan;
use Stellar\Navigation\File\Exceptions\FailedOnCopyFile;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\File\Exceptions\TryCopyFileButAlreadyExists;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

class Directory extends Path
{
    use Scan;

    /**
     * @param string $path
     * @param bool $recursive
     * @param int $permissions
     * @param bool $force
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     */
    public static function create(
        string $path,
        bool   $recursive = false,
        int    $permissions = 0777,
        bool   $force = false
    ): bool
    {
        if (self::exist($path) && $force === false) {
            throw new DirectoryAlreadyExist($path);
        }

        if (mkdir($path, $permissions, $recursive) === false) {
            throw new FailedOnCreateDirectory($path);
        }

        return true;
    }

    /**
     * @param string $directory_path
     * @param bool $force
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteDirectory
     * @throws FailedOnDeleteFile
     * @throws PathNotFound
     */
    public static function delete(string $directory_path, bool $force = false, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $directory_path = static::realPath($directory_path);
        }

        if (!empty($scan = self::scan(
            $directory_path,
            is_real_path: true,
            key_as_path: true,
            return_full_path: true,
            exclude_parents: true
        ))) {
            if ($force === false) {
                throw new FailedOnDeleteDirectory($directory_path);
            }

            foreach ($scan as $single_element) {
                if (Path::isDirectory($single_element)) {
                    self::delete($single_element, true, true);
                } else {
                    File::delete($single_element, true);
                }
            }
        }

        if (rmdir($directory_path) === false) {
            throw new FailedOnDeleteDirectory($directory_path);
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $final_path
     * @param bool $is_real_path
     * @param bool $recursive
     * @param int $permissions
     * @param bool $ignore_root
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     * @throws TryCopyFileButAlreadyExists
     */
    public static function copy(
        string $path,
        string $final_path,
        bool   $is_real_path = false,
        bool   $recursive = false,
        int    $permissions = 0777,
        bool   $ignore_root = false
    ): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        $scan = self::scan($path, is_real_path: true, key_as_path: true, return_full_path: true, exclude_parents: true);

        if ($ignore_root === false) {
            $final_path = StrTool::forceFinishWith($final_path, '/') . basename($path);

            try {
                self::create($final_path, true);
            } catch (DirectoryAlreadyExist) {

            }
        }

        foreach ($scan as $single_element) {
            if (Path::isDirectory($single_element)) {
                self::copy($single_element, $final_path, true, $recursive, $permissions);
            } else {
                File::copy($single_element, $final_path, is_real_path: true);
            }
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $final_path
     * @param bool $is_real_path
     * @param int $permissions
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws FailedOnDeleteDirectory
     * @throws FailedOnDeleteFile
     * @throws PathNotFound
     * @throws TryCopyFileButAlreadyExists
     */
    public static function move(
        string $path,
        string $final_path,
        bool   $is_real_path = false,
        int    $permissions = 0777,
    ): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        self::copy($path, $final_path, true, true, $permissions);

        self::delete($path, true, true);

        return true;
    }
}