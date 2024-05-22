<?php

namespace Stellar\Storage\Commands;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use Cosmo\Option;
use Cosmo\Option\Enums\OptionMode;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Directory\Exceptions\FailedOnDeleteDirectory;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Symlink;
use Stellar\Navigation\Symlink\Exceptions\FailedToCreateSymlink;
use Stellar\Navigation\Symlink\Exceptions\FailedToDeleteSymlink;
use Stellar\Navigation\Symlink\Exceptions\SymlinkAlreadyExist;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;

class StorageLink extends Command
{
    protected function name(): string
    {
        return 'storage:link';
    }

    /**
     * @return CommandResponse
     * @throws DirectoryAlreadyExist
     * @throws FailedOnDeleteDirectory
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws InvalidSettingException
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     * @throws FailedOnDeleteFile
     */
    protected function handle(): CommandResponse
    {
        $drives = $this->getStorageDrives();

        if (empty($drives)) {
            $this->warningBlock('No enabled drives to be linked!');

            return CommandResponse::SUCCESS;
        }

        $drive_public_path = public_path() . '/storage';

        if (Directory::exist($drive_public_path, true)) {
            if ($this->input->getOption('force') === false) {
                $this->debuggerBlock('Storage already created... skipping, use "-f" to force recreate.');

                return CommandResponse::FAILED;
            }

            Directory::delete($drive_public_path, true, true);
        }

        if (Directory::notExist($drive_public_path, true)) {
            Directory::create($drive_public_path, true);
        }

        foreach ($drives as $drive) {
            $this->createDriveSymlink($drive_public_path, $drive);
        }

        $this->successBlock('Storage symlinks were created successfully!');

        return CommandResponse::SUCCESS;
    }

    /**
     * @return array
     * @throws InvalidSettingException
     */
    protected function getStorageDrives(): array
    {
        $drives = [];

        foreach (Setting::get(SettingKey::StorageDrives->value, []) as $drive_name => $drive) {
            if (isset($drive['partitions']['public']) && $drive['partitions']['public'] === true) {
                $drives[] = $drive_name;
            }
        }

        return $drives;
    }

    protected function options(): array
    {
        return [
            Option::make(
                'force',
                '-f',
                'If storage already linked, force relink drives.',
                OptionMode::None
            ),
        ];
    }

    /**
     * @param string $drive_public_path
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedOnDeleteDirectory
     * @throws FailedOnDeleteFile
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     */
    protected function flushSymlinkPublicDirectory(string $drive_public_path): void
    {

    }

    /**
     * @param string $drive_public_path
     * @param string $drive
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws FailedToCreateSymlink
     * @throws FailedToDeleteSymlink
     * @throws PathNotFound
     * @throws SymlinkAlreadyExist
     */
    protected function createDriveSymlink(string $drive_public_path, string $drive): void
    {
        $drive_target_path = storage_path() . "/drives/$drive/public";

        if (Symlink::exist($drive_public_path . "/$drive")) {
            return;
        }

        Symlink::create($drive_target_path, $drive_public_path, $drive, true);
    }
}