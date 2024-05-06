<?php

namespace Stellar\Storage\Commands;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandReturnStatus;

/** Display all created drives with partitions. */
class StorageDrives extends Command
{

    protected function name(): string
    {
        return 'storage:drives';
    }

    protected function handle(): CommandReturnStatus
    {
        // TODO: Implement handle() method.
    }
}