<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Symlink\Exceptions\FailedToCreateSymlink;
use Stellar\Navigation\Symlink\Exceptions\FailedToDeleteSymlink;
use Stellar\Navigation\Symlink\Exceptions\SymlinkAlreadyExist;
use Stellar\Navigation\Symlink\Exceptions\TryRenameNonSymlink;

class Symlink extends Path
{
    /**
     * @param string $target_path
     * @param string $link_directory_path
     * @param string|null $custom_link_name
     * @param bool $is_real_path
     * @param bool $recursive
     * @param bool $force
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     */
    public static function create(
        string  $target_path,
        string  $link_directory_path,
        ?string $custom_link_name = null,
        bool    $is_real_path = false,
        bool    $recursive = false,
        bool    $force = false
    ): bool
    {
        if (!$is_real_path) {
            $target_path = self::realPath($target_path);
        }

        if (!self::exist($link_directory_path)) {
            Directory::create($link_directory_path, $recursive);
        }

        $full_link_path = StrTool::forceFinishWith($link_directory_path, '/') .
            ($custom_link_name ?? basename($target_path));

        if (self::exist($full_link_path)) {
            if ($force === false) {
                throw new SymlinkAlreadyExist($target_path, $full_link_path);
            }

            self::delete($full_link_path, true);
        }

        if (symlink($target_path, $full_link_path) === false) {
            throw new FailedToCreateSymlink($target_path, $full_link_path);
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $final_name
     * @param bool $is_real_path
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     * @throws TryRenameNonSymlink
     */
    public static function rename(string $path, string $final_name, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = self::realPath($path);
        }

        if (self::isSymlink($path, true)) {
            $target_path = parent::realPath($path);

            if (unlink($path) === false) {
                throw new FailedToDeleteSymlink($path);
            }

            return self::create($target_path, StrTool::beforeLast($path, '/'), $final_name, true);
        }

        throw new TryRenameNonSymlink($path);
    }

    public static function realPath(string $relative_path): string
    {
        return StrTool::forceFinishWith(
                parent::realPath(StrTool::beforeLast($relative_path, '/')),
                '/'
            ) . StrTool::afterLast($relative_path, '/');
    }

    /**
     * @param string $path
     * @return string
     * @throws PathNotFound
     */
    public static function targetPath(string $path): string
    {
        return parent::realPath($path);
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return true
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     */
    public static function delete(string $path, bool $is_real_path = false): true
    {
        if (!$is_real_path) {
            $path = self::realPath($path);
        }

        if (unlink($path) === false) {
            throw new FailedToDeleteSymlink($path);
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $final_directory
     * @param bool $force
     * @param bool $is_real_path
     * @param bool $recursive
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     */
    public static function copy(
        string $path,
        string $final_directory,
        bool   $force = false,
        bool   $is_real_path = false,
        bool   $recursive = false
    ): bool
    {
        if (!$is_real_path) {
            $path = self::realPath($path);
        }

        return self::create(
            self::targetPath($path),
            $final_directory,
            is_real_path: true,
            recursive: $recursive,
            force: $force
        );
    }

    /**
     * @param string $origin_path
     * @param string $final_directory
     * @param bool $force
     * @param bool $is_real_path
     * @param bool $recursive
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     */
    public static function move(
        string $origin_path,
        string $final_directory,
        bool   $force = false,
        bool   $is_real_path = false,
        bool   $recursive = false
    ): bool
    {
        if (!$is_real_path) {
            $origin_path = self::realPath($origin_path);
        }

        return self::copy($origin_path, $final_directory, $force, true, $recursive) &&
            self::delete($origin_path, true);
    }
}