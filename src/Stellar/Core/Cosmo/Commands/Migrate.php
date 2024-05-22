<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Core\Log\Log;
use Core\Database\Query\Facades\Schema;
use Core\Exceptions\FailedOnDeleteDir;
use Core\Exceptions\FailedOnScanDirectory;
use Core\Exceptions\PathAlreadyExist;
use Core\Exceptions\PathNotFound;
use Core\Exceptions\TypeNotMatch;
use Core\Helpers\ClassManager;
use Core\Helpers\StrTool;
use Core\Structure\Dir;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'migrate',
    description: 'This command run the Migration files.'
)]
class Migrate extends VortexCommand
{
    private array $saved_migrations = [];
    private array $migrations_files = [];
    private int $step;
    private int $index = 0;

    protected function configure(): void
    {
        $this->setHelp('Run migrate to execute migration files.')->addArgument(
            'migrations',
            InputArgument::IS_ARRAY,
            'Migration file(s) to run'
        );
    }

    protected function handle(): CommandReturnStatus
    {
        $this->loadMigrations();
        $this->setCurrentStep();

        $input_migrations = array_unique($this->input->getArgument('migrations'));

        if (!empty($input_migrations)) {
            return $this->runByInput($input_migrations);
        }

        return $this->runAll();
    }

    private function runAll(): CommandReturnStatus
    {
        if (empty($needed_migrations = array_diff($this->migrations_files, $this->saved_migrations))) {
            $this->breakLine();
            $this->debuggerBlock('Nothing to migrate...');
            $this->breakLine();

            return CommandReturnStatus::SUCCESS;
        }

        foreach ($needed_migrations as $migration) {
            $this->callMigration($migration);
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    /**
     * @param array $migrations
     * @return CommandReturnStatus
     * @throws FailedOnDeleteDir
     * @throws FailedOnScanDirectory
     * @throws PathAlreadyExist
     * @throws PathNotFound
     * @throws TypeNotMatch
     */
    private function runByInput(array $migrations): CommandReturnStatus
    {
        foreach ($migrations as $migration) {
            if (!StrTool::endsWith($migration, '.php')) {
                $migration .= '.php';
            }

            if (!in_array($migration, $this->migrations_files)) {
                $this->failRow($migration, 'NOT FOUND');
                Log::error("Migration $migration not found");
                continue;
            }

            if (in_array($migration, $this->saved_migrations)) {
                $this->debugRow($migration, 'ALREADY RUN');
                Log::error("Migration $migration already run");
                continue;
            }

            $this->callMigration($migration);
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    private function loadMigrations(): void
    {
        $this->migrations_files = Dir::scan(ProjectPath::MIGRATIONS->value);

        foreach (Schema::select('migrations')->get() as $migration) {
            $this->saved_migrations[] = $migration['migration'];
        }
    }

    private function setCurrentStep(): void
    {
        $last_ran_migration = Schema::last('migrations');
        $this->step = $last_ran_migration->isEmpty() ? 0 : $last_ran_migration[0]['step'] + 1;
    }

    private function callMigration(string $migration_filename): void
    {
        if ($this->index === 0) {
            $this->indexRow('Migrations');
            $this->index = 1;
        }

        include_once Path::fullPath(ProjectPath::MIGRATIONS->additionalPath($migration_filename));

        $declared_classes = get_declared_classes();
        $class = $declared_classes[count($declared_classes) - 1];

        ClassManager::callStaticFunction($class, 'up');

        Schema::insert('migrations', [
            'migration' => $migration_filename,
            'step' => $this->step
        ])->get();

        $this->successRow($migration_filename, 'RUN');
    }
}
