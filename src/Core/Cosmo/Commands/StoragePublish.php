<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Exceptions\DiskNotCreated;
use Core\Exceptions\DiskNotEnabled;
use Core\Exceptions\FailedOnDeleteDir;
use Core\Exceptions\FailedOnScanDirectory;
use Core\Exceptions\PathAlreadyExist;
use Core\Exceptions\PathNotFound;
use Core\Exceptions\PathNotIsASymlink;
use Core\Exceptions\SettingFileNotExist;
use Core\Exceptions\SettingKeyNotFound;
use Core\Exceptions\TypeNotMatch;
use Core\Helpers\Setting;
use Core\Helpers\Storage;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'storage:publish',
)]
class StoragePublish extends VortexCommand
{
    /**
     * @return CommandReturnStatus
     * @throws DiskNotCreated
     * @throws DiskNotEnabled
     * @throws FailedOnDeleteDir
     * @throws FailedOnScanDirectory
     * @throws PathAlreadyExist
     * @throws PathNotFound
     * @throws SettingFileNotExist
     * @throws SettingKeyNotFound
     * @throws TypeNotMatch
     * @throws PathNotIsASymlink
     */
    protected function handle(): CommandReturnStatus
    {
        if (($disks = Setting::get('storage.disks', false)) === false) {
            throw new SettingKeyNotFound('disks');
        }

        if (!empty($disks)) {
            $this->indexRow('Disk');
        }

        $custom_disks = $this->input->getArgument('disks');
        $final_disks = $disks;

        if (!empty($custom_disks)) {
            $final_disks = [];

            foreach ($custom_disks as $custom_disk) {
                if (in_array($custom_disk, $disks)) {
                    $final_disks[$custom_disk] = true;
                }
            }
        }

        foreach ($final_disks as $disk => $is_enabled) {
            if ($is_enabled) {
                if (!Path::exist(ProjectPath::STORAGE->additionalPath($disk))) {
                    $this->failRow($disk, 'not found');
                    continue;
                }

                Storage::publishDisk($disk);
                $this->successRow($disk, 'created');
                continue;
            }

            $this->debugRow($disk, 'disabled');
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('disks', InputArgument::IS_ARRAY);
    }
}
