<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'make:seed',
    description: 'Create a new Seed class.'
)]
class MakeSeed extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'SeedClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::SEEDS;
    }

    protected function getIndex(): string
    {
        return 'Seeds';
    }

    protected function stubFileName(): string
    {
        return 'seed.php';
    }

    protected function trades(): array
    {
        $trades = ['MountSeed' => $this->class_name];

        if ($table = $this->input->getOption('table')) {
            $trades['$table'] = $table;
        }

        return $trades;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Seed.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Seed class name')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'Related table.');
    }
}
