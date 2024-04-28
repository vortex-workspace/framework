<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Helpers\DateTime;
use Core\Helpers\StrTool;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:migration',
    description: 'Create a new Migration.'
)]
class MakeMigration extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'MigrationClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::MIGRATIONS;
    }

    protected function getIndex(): string
    {
        return 'Migrations';
    }

    protected function stubFileName(): string
    {
        return 'migration.php';
    }

    protected function trades(): array
    {
        return ['MountMigration' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new migration.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New migration file name');
    }

    protected function setClassName(): void
    {
        parent::setClassName();

        $migration_name = lcfirst($this->class_name);
        $migration_name = StrTool::snakeCase(DateTime::currentDateTime() . "_$migration_name") . '.php';
        $this->class_filename = str_replace(':', '_', $migration_name);
    }
}
