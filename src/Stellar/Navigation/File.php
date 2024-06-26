<?php

namespace Stellar\Navigation;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\File\Enums\UpdatePosition;
use Stellar\Navigation\File\Exceptions\FailedOnCopyFile;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\File\Exceptions\FailedOnGetFileContent;
use Stellar\Navigation\File\Exceptions\FailedOnMoveFile;
use Stellar\Navigation\File\Exceptions\TryCopyFileButAlreadyExists;
use Stellar\Navigation\File\Traits\Create;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream\Enums\OpenMode;
use Stellar\Navigation\Stream\Exceptions\FailedToOpenStream;
use Stellar\Navigation\Stream\Exceptions\FailedToWriteFromStream;
use Stellar\Navigation\Stream\Exceptions\MissingOpenedStream;


class File extends Path
{
    use Create;

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
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
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
     * @param string $final_directory
     * @param string|null $custom_filename
     * @param bool $is_real_path
     * @param bool $recursive
     * @param bool $force
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     * @throws TryCopyFileButAlreadyExists
     */
    public static function copy(
        string  $file_path,
        string  $final_directory,
        ?string $custom_filename = null,
        bool    $is_real_path = false,
        bool    $recursive = false,
        bool    $force = false
    ): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
            $final_directory = static::realPath($final_directory);
        }

        if (!self::exist($file_path)) {
            throw new PathNotFound($file_path);
        }

        $final_path = StrTool::forceFinishWith($final_directory, '/') . ($custom_filename === null ?
                basename($file_path) :
                StrTool::removeIfStartAndFinishWith($custom_filename, '/'));

        if (self::exist($final_path) && $force === false) {
            throw new TryCopyFileButAlreadyExists($file_path, $final_path);
        }

        if (!self::exist($final_directory)) {
            if ($recursive === false) {
                throw new PathNotFound($final_directory);
            }

            Directory::create($final_directory, true, true);
        }

        if (copy($file_path, $final_path) === false) {
            throw new FailedOnCopyFile($file_path, $final_path);
        }

        return true;
    }

    /**
     * @param string $file_path
     * @param bool $is_real_path
     * @param bool $use_include_path
     * @param int $offset
     * @param int|null $length
     * @return string
     * @throws FailedOnGetFileContent
     * @throws PathNotFound
     */
    public static function get(
        string $file_path,
        bool   $is_real_path = false,
        bool   $use_include_path = false,
        int    $offset = 0,
        ?int   $length = null
    ): string
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        if (self::notExist($file_path)) {
            throw new PathNotFound($file_path);
        }

        if (!($content = file_get_contents($file_path, $use_include_path, offset: $offset, length: $length))) {
            throw new FailedOnGetFileContent($file_path, $offset, $length);
        }

        return $content;
    }

    /**
     * @param string $file_path
     * @param string $content
     * @param int|null $length
     * @param bool $is_real_path
     * @return bool
     * @throws FailedOnDeleteFile
     * @throws FailedToOpenStream
     * @throws PathNotFound
     * @throws FailedToWriteFromStream
     * @throws MissingOpenedStream
     */
    public static function update(
        string $file_path,
        string $content,
        ?int   $length = null,
        bool   $is_real_path = false
    ): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        if (self::notExist($file_path)) {
            throw new PathNotFound($file_path);
        }

        self::delete($file_path);

        Stream::make($file_path, OpenMode::WB_MODE)->open()->write($content, $length);

        return true;
    }

    /**
     * @param string $file_path
     * @param array $replace
     * @param int|null $limit
     * @param int|null $length
     * @param bool $is_real_path
     * @return true
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws MissingOpenedStream
     * @throws PathNotFound
     */
    public static function replace(
        string $file_path,
        array  $replace,
        ?int   $limit = null,
        ?int   $length = null,
        bool   $is_real_path = false
    ): bool
    {
        if (!$is_real_path) {
            $file_path = static::realPath($file_path);
        }

        $content = self::get($file_path, true);

        foreach ($replace as $old => $new) {
            $content = StrTool::replace($content, $old, $new, $limit ?? -1);
        }

        self::update($file_path, $content, $length, true);

        return true;
    }

    /**
     * This will put the new content in the end of file or in the start of file.
     * @param string $path
     * @param string $content
     * @param UpdatePosition $position
     * @param bool $is_real_path
     * @param bool $break_line
     * @return bool
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws MissingOpenedStream
     * @throws PathNotFound
     */
    public static function put(
        string         $path,
        string         $content,
        UpdatePosition $position = UpdatePosition::End,
        bool           $is_real_path = false,
        bool           $break_line = false
    ): bool
    {
        if (!$is_real_path) {
            $path = static::realPath($path);
        }

        $file_content = self::get($path, true);

        $content = $position === UpdatePosition::Start ?
            $content . ($break_line ? "\n" : '') . $file_content :
            $file_content . ($break_line ? "\n" : '') . $content;

        self::update($path, $content, is_real_path: $is_real_path);

        return true;
    }
}