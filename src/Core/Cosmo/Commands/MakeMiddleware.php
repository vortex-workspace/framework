<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:middleware',
    description: 'Create a new Middleware class.'
)]
class MakeMiddleware extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'MiddlewareClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::MIDDLEWARES;
    }

    protected function getIndex(): string
    {
        return 'Middlewares';
    }

    protected function stubFileName(): string
    {
        return 'middleware.php';
    }

    protected function trades(): array
    {
        return ['MountMiddleware' => $this->class_name];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Middleware.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Middleware class name');
    }
}
