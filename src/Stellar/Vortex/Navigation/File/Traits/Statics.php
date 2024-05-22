<?php

namespace Stellar\Vortex\Navigation\File\Traits;

use Stellar\Core\Helpers\ArrayTool;
use Stellar\Core\Navigation\File\Exceptions;
use Stellar\Vortex\Navigation\Enums\FOpenMode;
use Stellar\Vortex\Navigation\File;
use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnCopyException;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnDeleteException;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnOpenStreamException;
use Stellar\Vortex\Navigation\Path\Exceptions\PathAlreadyExistException;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;

trait Statics
{
    /**
     * @param string $name
     * @param string $path
     * @param bool $is_real_path
     * @param bool $force_mode
     * @return false|resource
     * @throws FailedOnOpenStreamException
     * @throws FailedOnDeleteException
     * @throws PathNotFoundException
     */
    public static function create(
        string $name,
        string $path,
        bool   $is_real_path = false,
        bool   $force_mode = false
    )
    {
        if (self::exist($file_path = self::mountFilePath($name, $path, $is_real_path))) {
            if ($force_mode === false) {
                return false;
            }

            self::delete($file_path, true);
        }

        return self::openFile($name, $path, FOpenMode::X_MODE, $is_real_path);
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteException
     * @throws PathNotFoundException
     */
    public static function delete(string $path, bool $is_real_path = false): bool
    {
        if (!$is_real_path) {
            $path = Path::fullPath($path);
        }

        if (!unlink($path)) {
            throw new FailedOnDeleteException(
                $path,
                __CLASS__,
                __METHOD__
            );
        }

        return true;
    }

    /**
     * @param string $path
     * @param bool $is_real_path
     * @return string|bool
     * @throws PathNotFoundException
     */
    public static function getContent(string $path, bool $is_real_path = false): string|bool
    {
        if (!$is_real_path) {
            $path = Path::fullPath($path);
        }

        return file_get_contents($path);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $custom_name
     * @param bool $is_real_path
     * @param bool $force_mode
     * @return bool
     * @throws FailedOnCopyException
     * @throws PathAlreadyExistException
     * @throws PathNotFoundException
     */
    public static function copy(
        string  $from,
        string  $to,
        ?string $custom_name = null,
        bool    $is_real_path = false,
        bool    $force_mode = false
    ): bool
    {
        if (!$is_real_path) {
            $from = Path::fullPath($from);
            $to = Path::fullPath($to);
        }

        if ($custom_name !== null) {
            $to = "$to/$custom_name";
        } else {
            $to = $to . '/' . ArrayTool::last(explode('/', $from));
        }

        if (File::exist($to, true) && !$force_mode) {
            throw new PathAlreadyExistException($to);
        }

        if (copy($from, $to) === false) {
            throw new FailedOnCopyException($to, $from, __CLASS__, __METHOD__);
        }

        return true;
    }

    /**
     * @param string $new_name
     * @param string $path
     * @param bool $is_real_path
     * @return bool
     * @throws PathNotFoundException
     */
    public static function rename(string $new_name, string $path, bool $is_real_path = false): bool
    {
        File::exist($path, $is_real_path);

        $final_path = substr(
            $path,
            0,
            strlen($path) - strlen(ArrayTool::last(explode('/', $path)))
        );

        return rename($path, $final_path . '/' . $new_name);
    }
}