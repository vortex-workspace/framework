<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\File\Exceptions\FailedOnMoveFile;
use Stellar\Navigation\File\Exceptions\FailedOnRenameFile;
use Stellar\Navigation\File\Exceptions\FileAlreadyExists;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Pointer\Enums\OpenMode;
use Stellar\Navigation\Pointer\Exceptions\FailedToClosePointer;
use Stellar\Navigation\Pointer\Exceptions\FailedToOpenFilePointer;
use Stellar\Navigation\Pointer\Exceptions\TryCloseNonOpenedPointer;

class File extends Path
{
    /**
     * @param string $filename
     * @param string $directory_path
     * @param bool $is_real_path
     * @param bool $force
     * @param OpenMode $mode
     * @param bool $exception_mode
     * @return bool
     * @throws FailedOnDeleteFile
     * @throws FailedToClosePointer
     * @throws FailedToOpenFilePointer
     * @throws FileAlreadyExists
     * @throws PathNotFound
     * @throws TryCloseNonOpenedPointer
     */
    public static function create(
        string   $filename,
        string   $directory_path,
        bool     $is_real_path = false,
        bool     $force = false,
        OpenMode $mode = OpenMode::X_PLUS_MODE,
        bool     $exception_mode = true
    ): bool
    {
        if (!$is_real_path) {
            $directory_path = static::realPath($directory_path);
        }

        if (Path::exist($full_path = "$directory_path/$filename")) {
            if ($force === false) {
                throw new FileAlreadyExists($full_path);
            }

            self::delete($full_path);
        }

        $pointer = Pointer::make($full_path, $mode);

        if ($exception_mode) {
            $pointer->tryOpen();
            $pointer->tryClose();

            return true;
        }

        return $pointer->open() && $pointer->close();
    }

    /**
     * @param string $file_path
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteFile
     * @throws PathNotFound
     */
    public static function delete(string $file_path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        if (unlink($file_path) === false) {
            throw new FailedOnDeleteFile($file_path);
        }

        return true;
    }

    /**
     * @param string $file_path
     * @param string $final_directory
     * @param bool $is_real_path
     * @param bool $recursive
     * @return bool
     * @throws FailedOnMoveFile
     * @throws PathNotFound
     */
    public static function move(
        string $file_path,
        string $final_directory,
        bool   $is_real_path = false,
        bool   $recursive = false
    ): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        if (!Path::exist($final_directory)) {
            if ($recursive === false) {
                throw new PathNotFound($final_directory);
            }

            Directory::create($final_directory, recursive: true);
        }

        $final_path = StrTool::forceFinishWith($final_directory, '/') . basename($file_path);

        if (rename($file_path, $final_path) === false) {
            throw new FailedOnMoveFile($file_path, $final_path);
        }

        return true;
    }

    /**
     * @param string $file_path
     * @param string $final_filename
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnRenameFile
     * @throws PathNotFound
     */
    public static function rename(string $file_path, string $final_filename, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        $final_path = StrTool::replace(
            $file_path,
            basename($file_path),
            StrTool::removeIfStartAndFinishWith($final_filename, '/')
        );

        if (rename($file_path, $final_path) === false) {
            throw new FailedOnRenameFile($file_path, $final_filename);
        }

        return true;
    }
}