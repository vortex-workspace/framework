<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:controller',
    description: 'Create a new Controller class.'
)]
class MakeController extends BaseMakeCommand
{
    protected ?string $file_name = null;

    protected function getArgumentName(): string
    {
        return 'ControllerClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::CONTROLLERS;
    }

    protected function getIndex(): string
    {
        return 'Controllers';
    }

    protected function stubFileName(): string
    {
        return $this->input->getOption('api') ? 'api_controller.php' : 'controller.php';
    }

    protected function trades(): array
    {
        return ['MountController' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Controller.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Controller file name')
            ->addOption('api', 'a');
    }
}
