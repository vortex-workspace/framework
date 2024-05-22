<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:service',
    description: 'Create a new Service class.'
)]
class MakeService extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'ServiceClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::SERVICES;
    }

    protected function getIndex(): string
    {
        return 'Services';
    }

    protected function stubFileName(): string
    {
        return 'service.php';
    }

    protected function trades(): array
    {
        return ['MountService' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Service.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Service file name');
    }
}
