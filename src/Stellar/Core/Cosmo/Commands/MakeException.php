<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:exception',
    description: 'Create a new Exception class.'
)]
class MakeException extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'ExceptionClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::EXCEPTIONS;
    }

    protected function getIndex(): string
    {
        return 'Exceptions';
    }

    protected function stubFileName(): string
    {
        return 'exception.php';
    }

    protected function trades(): array
    {
        return ['MountException' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Exception.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Exception class name');
    }
}
