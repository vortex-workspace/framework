<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Helpers\Environment;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\SchemaEngine\AutoRelation\DiscoverRelations;
use Stellar\Core\Cosmo\SchemaEngine\AutoRelation\ModelManager;
use Stellar\Core\Cosmo\SchemaEngine\SchemaMapper;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\StringWrapper;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'DB:translate',
)]
class DatabaseTranslate extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        if (empty(($schema = new SchemaMapper())->tables)) {
            $this->breakLine();
            $this->debuggerBlock('No tables found in schema "' . Environment::dbDatabase() . '".');
            $this->breakLine();

            return CommandReturnStatus::SUCCESS;
        }

        $with_pivot_model = $this->input->getOption('pivot-model');

        $relations = (new DiscoverRelations())->setRelations();
        $traceRelations = $relations->setTraceRelations();
        $models_traces = $traceRelations->getTracesAndModels();

        $this->breakLine();
        $this->defaultTable(
            [(new StringWrapper('TRACE'))->foreground(ConsoleStyleColor::WHITE)->wrap()],
            $traceRelations->getFormattedTraceTableRows()
        )->render();
        $this->breakLine();
        ModelManager::mount($models_traces['traces'], $models_traces['tables'], $relations->getPrimaryRelations(), $this);
        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Translate the database inside Vortex Models structure.')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }
}
