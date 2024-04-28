<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:policy',
    description: 'Create a new Policy class.'
)]
class MakePolicy extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'PolicyClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::POLICIES;
    }

    protected function getIndex(): string
    {
        return 'Policies';
    }

    protected function stubFileName(): string
    {
        return 'policy.php';
    }

    protected function trades(): array
    {
        return ['MountPolicy' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Policy.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Policy class name');
    }
}
