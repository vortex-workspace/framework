<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Helpers\StrTool;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'make:model',
    description: 'Create a new Model class.'
)]
class MakeModel extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'ModelClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::MODELS;
    }

    protected function getIndex(): string
    {
        return 'Models';
    }

    protected function stubFileName(): string
    {
        return 'model.php';
    }

    protected function trades(): array
    {
        return [
            'MountModel' => $this->class_name,
            'table_name' => StrTool::pluralize(StrTool::snakeCase($this->class_name)),
        ];
    }

    protected function configure()
    {
        $this->setHelp('Create a new Model.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Model class name');
    }
}
