<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:factory',
    description: 'Create a new Factory class.'
)]
class MakeFactory extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'FactoryClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::FACTORIES;
    }

    protected function getIndex(): string
    {
        return 'Factories';
    }

    protected function stubFileName(): string
    {
        return 'factory.php';
    }

    protected function trades(): array
    {
        return ['MountFactory' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Factory.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Factory class name');
    }
}
