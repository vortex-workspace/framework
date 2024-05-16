<?php

namespace Stellar\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Stellar\Boot\Application;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\File;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Storage\Exceptions\DriveNotDefined;
use Stellar\Storage\StorageDrive\Exceptions\DrivePartitionIsDisabled;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnGetContent;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnGetMimeType;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnPutFile;
use Stellar\Storage\StorageDrive\Exceptions\UrlBlockedForPrivatePartition;
use Stellar\Storage\StorageDrive\Traits\ManagePartition;

class StorageDrive
{
    use ManagePartition;

    public const string PUBLIC_PARTITION = 'public';
    public const string PRIVATE_PARTITION = 'private';

    protected Filesystem $filesystem;
    protected ?string $drive;
    protected ?string $partition;
    protected ?bool $exception_mode = null;
    protected ?bool $public_partition_is_enabled = null;
    protected ?bool $private_partition_is_enabled = null;

    /**
     * @param string|null $drive
     * @param string|null $partition
     * @throws InvalidSettingException
     */
    public function __construct(?string $drive = null, ?string $partition = null)
    {
        if ($drive === null) {
            $drive = Setting::get(SettingKey::StorageDefaultDrive->value, 'local');
        }

        if ($partition === null) {
            $partition = self::PUBLIC_PARTITION;
        }

        $this->drive = $drive;
        $this->partition = $partition;
    }

    public function drive(string $drive): static
    {
        $this->drive = $drive;

        return $this;
    }

    public function publicPartition(): static
    {
        $this->partition = self::PUBLIC_PARTITION;

        return $this;
    }

    public function privatePartition(): static
    {
        $this->partition = self::PRIVATE_PARTITION;

        return $this;
    }

    public function exceptionMode(bool $enable = true): static
    {
        $this->exception_mode = $enable;

        return $this;
    }

    public function enablePublicPartition(bool $enable = true): static
    {
        $this->public_partition_is_enabled = $enable;

        return $this;
    }

    public function enablePrivatePartition(bool $enable = true): static
    {
        $this->private_partition_is_enabled = $enable;

        return $this;
    }

    /**
     * @param string $path
     * @param string|null $partition
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     */
    public function exists(string $path, ?string $partition = null): bool
    {
        $this->setupDriveSettings();
        $partition = $partition ?? $this->partition;
        $path = "storage/drives/$this->drive/$partition" . StrTool::forceStartWith($path, '/');

        return File::exist($path);
    }

    /**
     * @param string $path
     * @return string|bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnGetMimeType
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public function mimeType(string $path): string|bool
    {
        $this->setupDriveSettings();
        $path = "drives/$this->drive/$this->partition" . StrTool::forceStartWith($path, '/');

        try {
            storage_path($path);
        } catch (PathNotFound $exception) {
            if ($this->exception_mode === true) {
                throw new PathNotFound($exception->path);
            }

            return false;
        }

        try {
            return Application::getInstance()
                ->getFilesystem()
                ->mimeType("storage/$path");
        } catch (FilesystemException $exception) {
            if ($this->exception_mode === true) {
                throw new FailedOnGetMimeType("storage/$path", $exception->getMessage());
            }

            return false;
        }
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
    public function url(string $path): string|bool
    {
        if ($this->partition === self::PRIVATE_PARTITION && $this->exception_mode === true) {
            throw new UrlBlockedForPrivatePartition($this->drive);
        }

        $this->setupDriveSettings();

        try {
            storage_path("drives/$this->drive/public" . StrTool::forceStartWith($path, '/'));
        } catch (PathNotFound $exception) {
            if ($this->exception_mode === true) {
                throw new PathNotFound($exception->path);
            }

            return false;
        }

        return StrTool::forceFinishWith(env('APP_URL'), '/') .
            "storage/$this->drive/" . StrTool::removeIfStartAndFinishWith($path, '/');
    }

    /**
     * @param string $location
     * @param string|Stream $content
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnPutFile
     * @throws InvalidSettingException
     */
    public function put(string $location, string|Stream $content): bool
    {
        $this->setupDriveSettings();
        $filesystem = Application::getInstance()->getFilesystem();
        $location = "storage/drives/$this->drive/$this->partition/$location";

        try {
            if ($content instanceof Stream) {
                $filesystem->writeStream($location, $content->getResource());

                return true;
            }

            $filesystem->write($location, $content);

            return true;
        } catch (FilesystemException $exception) {
            if ($this->exception_mode === true) {
                throw new FailedOnPutFile($location, $exception->getMessage());
            }

            return false;
        }
    }

    /**
     * @param string $path
     * @return string|bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnGetContent
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public function get(string $path): string|bool
    {
        $this->setupDriveSettings();
        $path = "drives/$this->drive/$this->partition" . StrTool::forceStartWith($path, '/');
        storage_path($path);

        try {
            return Application::getInstance()
                ->getFilesystem()
                ->read("storage/$path");
        } catch (FilesystemException $exception) {
            if ($this->exception_mode === true) {
                throw new FailedOnGetContent($path, $exception->getMessage());
            }

            return false;
        }
    }

    /**
     * @param string $path
     * @return false|string
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public function path(string $path): bool|string
    {
        $this->setupDriveSettings();

        $path = "drives/$this->drive/$this->partition" . StrTool::forceStartWith($path, '/');

        if ($this->exception_mode === true) {
            return storage_path($path);
        }

        if (File::exist("storage/$path")) {
            return storage_path($path);
        }

        return false;
    }

    /**
     * @param string $drive
     * @return array
     * @throws DriveNotDefined
     * @throws InvalidSettingException
     */
    protected function getDriveSettings(string $drive): array
    {
        if (($drive = Setting::get("storage.drives.$drive")) === null) {
            throw new DriveNotDefined($drive);
        }

        return $drive;
    }

    /**
     * @return void
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws InvalidSettingException
     */
    private function setupDriveSettings(): void
    {
        $this->filesystem = Application::getInstance()->getFilesystem();
        $drive_settings = $this->getDriveSettings($this->drive);

        $this->public_partition_is_enabled = $drive_settings['partitions']['public'] ?? true;
        $this->private_partition_is_enabled = $drive_settings['partitions']['private'] ?? false;
        $this->exception_mode = $drive_settings['exception_mode'] ?? true;

        if ($this->partition === self::PRIVATE_PARTITION && $this->private_partition_is_enabled === false) {
            throw new DrivePartitionIsDisabled($this->drive, $this->partition);
        }

        if ($this->partition === self::PUBLIC_PARTITION && $this->public_partition_is_enabled === false) {
            throw new DrivePartitionIsDisabled($this->drive, $this->partition);
        }
    }
}