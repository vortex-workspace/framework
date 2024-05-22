<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Helpers\ClassManager;
use Core\Helpers\StrTool;
use Core\Structure\Dir;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'seed',
    description: 'This command run seed files.'
)]
class Seed extends VortexCommand
{
    private bool $has_index = false;

    protected function handle(): CommandReturnStatus
    {
        $file_names = array_unique($this->input->getArgument('name'));
        $project_seeds = Dir::scan(ProjectPath::SEEDS->value);

        if (!empty($file_names)) {
            foreach ($file_names as $file_name) {
                if (!StrTool::endsWith($file_name, '.php')) {
                    $file_name .= '.php';
                }

                if (!in_array($file_name, $project_seeds)) {
                    $this->indexRow('Seed');
                    $this->debugRow($file_name, 'not found');
                    continue;
                }

                $this->indexRow('Seed');
                $this->callSeed($file_name);
                $this->successRow($file_name, 'run');
            }

            $this->breakLine(2);

            return CommandReturnStatus::SUCCESS;
        }

        foreach ($project_seeds as $seed) {
            $this->indexRow('Seed');
            $this->callSeed($seed);
            $this->successRow($seed, 'run');
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run seeder to execute seed files.')
            ->addArgument('name', InputArgument::IS_ARRAY, 'Seed file(s) name');
    }

    private function callSeed(string $seed): void
    {
        include_once ProjectPath::SEEDS->additionalPath($seed);
        ClassManager::callStaticFunction($this->mountClassNamespace($seed), 'handler');
    }

    private function mountClassNamespace(string $class_name)
    {
        return str_replace(
            '/',
            '\\',
            substr(ProjectPath::SEEDS->additionalPath($class_name), 0, -4)
        );
    }
}
