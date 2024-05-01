<?php

namespace Stellar\Navigation;

use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Directory\Exceptions\FailedOnDeleteDirectory;
use Stellar\Navigation\Directory\Traits\Scan;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

class Directory extends Path
{
    use Scan;

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
}