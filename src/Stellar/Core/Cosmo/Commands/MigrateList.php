<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Database\Query\Facades\Schema;
use Core\Structure\Dir;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'migrate:list',
    description: 'This command list all Migrations.'
)]
class MigrateList extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        $this->indexRow('Migrations');
        $saved_migrations = [];

        foreach (Schema::select('migrations')->get() as $migration) {
            $saved_migrations[] = $migration['migration'];
        }

        $project_migrations = Dir::scan(ProjectPath::MIGRATIONS->value, return_key_equals_value: false);
        $all_migrations = array_unique(array_merge($project_migrations, $saved_migrations));

        foreach ($all_migrations as $migration) {
            if (in_array($migration, $saved_migrations)) {
                $this->successRow($migration, 'already run');
                continue;
            }

            $this->failRow($migration, 'not run');
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run migrate to execute migration files.');
    }
}
