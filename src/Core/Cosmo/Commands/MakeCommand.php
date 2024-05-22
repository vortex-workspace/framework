<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:command',
    description: 'Create a new Command class.'
)]
class MakeCommand extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'CommandClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::COMMANDS;
    }

    protected function getIndex(): string
    {
        return 'Commands';
    }

    protected function stubFileName(): string
    {
        return 'command.php';
    }

    protected function trades(): array
    {
        return [
            'commandName' => $this->class_name,
            'MakeCommandName' => $this->class_name
        ];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Command.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Command class name');
    }
}
