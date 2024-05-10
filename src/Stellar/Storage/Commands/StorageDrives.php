<?php

namespace Stellar\Storage\Commands;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use Stellar\Facades\Collection;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Symlink;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Storage\S3\Client;

/** Display all created drives with partitions. */
class StorageDrives extends Command
{

    protected function name(): string
    {
        return 'storage:drives';
    }

    /**
     * @return CommandResponse
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    protected function handle(): CommandResponse
    {
        if (($formatedDrives = $this->formatDrivesArray())->empty()) {
            $this->debuggerBlock('No drives configured to display!');

            return CommandResponse::SUCCESS;
        }

        $this->table($this->mountTableHeaders(), $formatedDrives);

        return CommandResponse::SUCCESS;
    }

    /**
     * @return Collection
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    private function formatDrivesArray(): Collection
    {
        $formated_drives = new Collection([]);
        $id = 0;

        foreach (Setting::get(SettingKey::StorageDrives->value, []) as $drive_name => $drive) {
            $formated_drives->push([
                'ID' => '<fg=cyan>' . $id++ . '</>',
                'Drive' => $drive_name,
                'Public' => ($drive['partitions']['public'] ?? false) ? '<fg=green>Enabled</>' : '<fg=red>Disabled</>',
                'Private' => ($drive['partitions']['private'] ?? false) ? '<fg=green>Enabled</>' : '<fg=red>Disabled</>',
                'Status' => Symlink::exist(public_path() . "/storage/$drive_name") ?  '<fg=green>  ●</>' : '  ○'
            ]);
        }

        return $formated_drives;
    }

    private function mountTableHeaders(): array
    {
        return [
            'ID',
            'Drive',
            'Public',
            'Private',
            'Status',
        ];
    }
}