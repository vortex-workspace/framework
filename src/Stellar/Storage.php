<?php

namespace Stellar;

use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Storage\Exceptions\DriveNotDefined;
use Stellar\Storage\StorageDrive;
use Stellar\Storage\StorageDrive\Exceptions\DrivePartitionIsDisabled;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnGetContent;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnGetMimeType;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnPutFile;
use Stellar\Storage\StorageDrive\Exceptions\UrlBlockedForPrivatePartition;

class Storage
{
    /**
     * @param string $drive
     * @return StorageDrive
     * @throws InvalidSettingException
     */
    public static function drive(string $drive): StorageDrive
    {
        return new StorageDrive($drive);
    }

    public static function privatePartition(): StorageDrive
    {
        return new StorageDrive(partition: 'private');
    }

    public static function publicPartition(): StorageDrive
    {
        return new StorageDrive(partition: 'public');
    }

    public static function exceptionMode(bool $enable = true): StorageDrive
    {
        return (new StorageDrive())->exceptionMode($enable);
    }

    public static function enablePublicPartition(bool $enable = true): StorageDrive
    {
        return (new StorageDrive())->enablePublicPartition($enable);
    }

    public static function enablePrivatePartition(bool $enable = true): StorageDrive
    {
        return (new StorageDrive())->enablePrivatePartition($enable);
    }

    /**
     * @param string $path
     * @param string|null $partition
     * @return bool
     * @throws InvalidSettingException
     * @throws DrivePartitionIsDisabled
     * @throws DriveNotDefined
     */
    public static function exists(string $path, ?string $partition = null): bool
    {
        return (new StorageDrive())->exists($path, $partition);
    }

    /**
     * @param string $path
     * @return string|bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws PathNotFound
     * @throws FailedOnGetMimeType
     */
    public static function mimeType(string $path): string|bool
    {
        return (new StorageDrive())->mimeType($path);
    }

    /**
     * @param string $path
     * @return string|bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws PathNotFound
     * @throws UrlBlockedForPrivatePartition
     */
    public static function url(string $path): string|bool
    {
        return (new StorageDrive())->url($path);
    }

    /**
     * @param string $location
     * @param string|Stream $content
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws FailedOnPutFile
     */
    public static function put(string $location, string|Stream $content): bool
    {
        return (new StorageDrive())->put($location, $content);
    }

    /**
     * @param string $path
     * @return string|bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws PathNotFound
     * @throws FailedOnGetContent
     */
    public static function get(string $path): string|bool
    {
        return (new StorageDrive())->get($path);
    }

    /**
     * @param string $path
     * @return bool|string
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function path(string $path): bool|string
    {
        return (new StorageDrive())->path($path);
    }

    /**
     * @param string $path
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws FailedOnDeleteFile
     * @throws PathNotFound
     */
    public static function delete(string $path): bool
    {
        return (new StorageDrive())->delete($path);
    }
}