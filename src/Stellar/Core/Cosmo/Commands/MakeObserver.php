<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Stellar\Core\Cosmo\BaseMakeCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'make:observer',
    description: 'Create a new Observer class.'
)]
class MakeObserver extends BaseMakeCommand
{
    protected function getArgumentName(): string
    {
        return 'ObserverClass';
    }

    protected function finalPath(): ProjectPath
    {
        return ProjectPath::OBSERVERS;
    }

    protected function getIndex(): string
    {
        return 'Observers';
    }

    protected function stubFileName(): string
    {
        return 'observer.php';
    }

    protected function trades(): array
    {
        $model = $this->input->getOption('model') ?? 'Model';

        $trades = [
            'MountObserver' => $this->class_name,
            'ModelClass' => $model,
            'model' => lcfirst($model),
        ];

        if ($model === 'Model') {
            $trades['App\Models\Model'] = 'Core\Abstractions\Model';
        }

        return $trades;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Observer.')
            ->addArgument($this->getArgumentName(), InputArgument::REQUIRED, 'New Observer class name.')
            ->addOption('model', 'm', InputOption::VALUE_OPTIONAL, 'Observer related Model class.');
    }
}
