<?php

namespace Stellar\Storage\StorageDrive\Traits;

use League\Flysystem\FilesystemException;
use Stellar\Boot\Application;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Storage\Exceptions\DriveNotDefined;
use Stellar\Storage\StorageDrive\Exceptions\DrivePartitionIsDisabled;
use Stellar\Storage\StorageDrive\Exceptions\FailedOnMoveFile;

trait ManagePartition
{
    /**
     * @param string $path
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnMoveFile
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public function turnPrivate(string $path): bool
    {
        return $this->changePartition($path, true);
    }

    /**
     * @param string $path
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnMoveFile
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public function turnPublic(string $path): bool
    {
        return $this->changePartition($path, false);
    }

    /**
     * @param string $path
     * @param bool $from_public
     * @return bool
     * @throws DriveNotDefined
     * @throws DrivePartitionIsDisabled
     * @throws FailedOnMoveFile
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    private function changePartition(string $path, bool $from_public): bool
    {
        $initial_partition = 'private';
        $final_partition = 'public';

        if ($from_public) {
            $initial_partition = 'public';
            $final_partition = 'private';
        }

        if (!$this->exists($path, $initial_partition)) {
            if ($this->exception_mode === true) {
                $path = "storage/drives/$this->drive/$initial_partition" . StrTool::forceStartWith($path, '/');
                throw new PathNotFound($path);
            }

            return false;
        }

        $path = "storage/drives/$this->drive/$initial_partition" . StrTool::forceStartWith($path, '/');

        $to = StrTool::replace($path,
            "storage/drives/$this->drive/$initial_partition",
            "storage/drives/$this->drive/$final_partition"
        );

        try {
            Application::getInstance()
                ->getFilesystem()
                ->move($path, $to);

            return true;
        } catch (FilesystemException) {
            if ($this->exception_mode === true) {
                throw new FailedOnMoveFile($path, $to);
            }

            return false;
        }
    }
}