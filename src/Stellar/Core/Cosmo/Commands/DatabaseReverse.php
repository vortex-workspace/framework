<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Exceptions\SchemaHasNoTables;
use Core\Exceptions\SchemaNotFound;
use Core\Exceptions\SchemaNotSet;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\SchemaEngine\Reverse\ReverseEngine;
use Stellar\Core\Cosmo\SchemaEngine\Schema;
use Stellar\Core\Cosmo\SchemaEngine\SchemaMapper;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'DB:reverse',
)]
class DatabaseReverse extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        try {
            Schema::existOrFail();
        } catch (SchemaNotFound|SchemaNotSet $exception) {
            $this->breakLine();
            $this->failBlock($exception->getMessage());
            $this->breakLine();

            return CommandReturnStatus::FAILED;
        }

        $tables = (new ReverseEngine(new SchemaMapper()))->getTablesOrder();

        $this->breakLine();

        foreach ($tables as $table) {
            $this->successRow($table, 'created');
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }
}
