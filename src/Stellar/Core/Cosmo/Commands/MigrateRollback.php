<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Database\Query\Facades\Schema;
use Core\Helpers\ClassManager;
use Core\Helpers\StrTool;
use Core\Structure\Dir;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'migrate:rollback',
    description: 'This command rollback the last step Migration files.'
)]
class MigrateRollback extends VortexCommand
{
    private ?int $step = null;
    private bool $has_index = false;

    protected function configure()
    {
        $this->setHelp('Run rollback migration to execute migration down.')
            ->addArgument('migrations', InputArgument::IS_ARRAY, 'Select the migrations to rollback')
            ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'Select the step to rollback');
    }

    protected function handle(): CommandReturnStatus
    {
        $this->setCurrentStep();
        $input_migrations = array_unique($this->input->getArgument('migrations'));
        $option_step = $this->input->getOption('step');

        if ($this->step === null) {
            $this->breakLine();
            $this->debuggerBlock('Nothing to rollback.');
            $this->breakLine();
            return CommandReturnStatus::SUCCESS;
        }

        if ($option_step === null && empty($input_migrations)) {
            $this->rollbackByStep($this->step);
            $this->breakLine(2);

            return CommandReturnStatus::SUCCESS;
        }

        if ($option_step !== null) {
            $this->rollbackByStep($option_step);
        }

        if (empty($input_migrations)) {
            $this->breakLine();
            return CommandReturnStatus::SUCCESS;
        }

        $this->rollbackByNames($input_migrations);
        $this->breakLine();
        return CommandReturnStatus::SUCCESS;
    }

    private function setCurrentStep(): void
    {
        $last_ran_migration = Schema::last('migrations');
        $this->step = $last_ran_migration->isEmpty() ? null : $last_ran_migration[0]['step'];
    }

    private function rollbackMigration(string $migration): bool
    {
        include Path::fullPath(ProjectPath::MIGRATIONS->additionalPath($migration));

        $classes = get_declared_classes();
        $class = $classes[count($classes) - 1];

        ClassManager::callStaticFunction($class, 'down');

        return Schema::delete('migrations')->where('migration', $migration)->get();
    }

    private function rollbackByStep(int $option_step): void
    {
        $need_rollback = [];

        if (!$migrations = Schema::select('migrations')->where('step', $option_step)->get()) {
            $this->warningBlock("Step $option_step not found.");
        } else {
            $this->has_index = true;
            $this->indexRow('Migrations');

            foreach ($migrations as $migration) {
                $need_rollback[] = $migration['migration'];
            }
        }

        foreach ($need_rollback as $migration) {
            if (!$this->rollbackMigration($migration)) {
                $this->failRow($migration, 'failed');
                continue;
            }

            $this->successRow($migration, 'rollback');
        }
    }

    private function rollbackByNames(array $input_migrations): void
    {
        $stored_migrations = Schema::select('migrations')->get()->getArrayCopy();
        $migrations = Dir::scan(ProjectPath::MIGRATIONS->value);

        foreach ($stored_migrations as $key => $stored_migration) {
            if (!in_array($stored_migration['migration'], $migrations)) {
                $this->debugRow($stored_migration, 'not found');
                unset($stored_migration[$key]);

                continue;
            }

            $stored_migrations[$key] = $stored_migration['migration'];
        }

        if (!$this->has_index) {
            $this->indexRow('Migrations');
        }

        foreach ($input_migrations as $input_migration) {
            if (!StrTool::endsWith($input_migration, '.php')) {
                $input_migration .= '.php';
            }

            if (in_array($input_migration, $stored_migrations)) {
                if (!$this->rollbackMigration($input_migration)) {
                    $this->failRow($input_migration, 'failed');
                    continue;
                }

                $this->successRow($input_migration, 'rollback');
                continue;
            }

            $this->debugRow($input_migration, 'not found');
        }

        $this->breakLine();
    }
}
