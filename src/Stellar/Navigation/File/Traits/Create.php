<?php

namespace Stellar\Navigation\File\Traits;

use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\File\Exceptions\FailedOnGetFileContent;
use Stellar\Navigation\File\Exceptions\FileAlreadyExists;
use Stellar\Navigation\Path;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream;
use Stellar\Navigation\Stream\Enums\OpenMode;
use Stellar\Navigation\Stream\Exceptions\FailedToCloseStream;
use Stellar\Navigation\Stream\Exceptions\FailedToOpenStream;
use Stellar\Navigation\Stream\Exceptions\FailedToWriteFromStream;
use Stellar\Navigation\Stream\Exceptions\MissingOpenedStream;
use Stellar\Navigation\Stream\Exceptions\TryCloseNonOpenedStream;

trait Create
{
    /**
     * @param string $filename
     * @param string $directory_path
     * @param bool $force
     * @param string|null $content
     * @param bool $recursive
     * @return bool
     * @throws Directory\Exceptions\DirectoryAlreadyExist
     * @throws Directory\Exceptions\FailedOnCreateDirectory
     * @throws FailedOnDeleteFile
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    private static function createFrom(
        string  $filename,
        string  $directory_path,
        bool    $force = false,
        ?string $content = null,
        bool    $recursive = false
    ): bool
    {
        if (Directory::notExist($directory_path)) {
            if ($recursive === false) {
                throw new PathNotFound($directory_path);
            }

            Directory::create($directory_path, true, force: true);
        }

        if (Path::exist($full_path = "$directory_path/$filename")) {
            if ($force === false) {
                throw new FileAlreadyExists($full_path);
            }

            self::delete($full_path);
        }

        $stream = Stream::make($full_path, OpenMode::X_PLUS_MODE)->open();

        if ($content !== null) {
            $stream->write($content);
        }

        $stream->close();

        return true;
    }

    /**
     * @param string $filename
     * @param string $directory_path
     * @param bool $force
     * @param bool $recursive
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedOnDeleteFile
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    public static function create(
        string $filename,
        string $directory_path,
        bool   $force = false,
        bool   $recursive = false
    ): bool
    {
        return self::createFrom($filename, $directory_path, $force, recursive: $recursive);
    }

    /**
     * @param string $filename
     * @param string $directory_path
     * @param string $content
     * @param bool $force
     * @param bool $recursive
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedOnDeleteFile
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    public static function createWithContent(
        string $filename,
        string $directory_path,
        string $content,
        bool   $force = false,
        bool   $recursive = false
    ): bool
    {
        return self::createFrom($filename, $directory_path, $force, $content, $recursive);
    }

    /**
     * @param string $filename
     * @param string $directory_path
     * @param string $template_path
     * @param array $replace
     * @param int|null $limit
     * @param bool $force
     * @param bool $recursive
     * @return bool
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    public static function createFromTemplate(
        string $filename,
        string $directory_path,
        string $template_path,
        array  $replace = [],
        ?int   $limit = null,
        bool   $force = false,
        bool   $recursive = false
    ): bool
    {
        $template_path = self::realPath($template_path);

        $content = self::get($template_path, true);

        foreach ($replace as $old => $new) {
            $content = StrTool::replace($content, $old, $new, $limit ?? -1);
        }

        return self::createFrom($filename, $directory_path, $force, $content, $recursive);
    }
}