<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Exceptions\SchemaHasNoTables;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\SchemaEngine\AutoRelation\DiscoverRelations;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\StringWrapper;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'DB:traces',
)]
class DatabaseTraces extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        try {
            $relations = (new DiscoverRelations())->setRelations()->setTraceRelations();
        } catch (SchemaHasNoTables $exception) {
            $this->breakLine();
            $this->debuggerBlock($exception->getMessage());
            $this->breakLine();

            return CommandReturnStatus::SUCCESS;
        }

        $with_pivot_model = $this->input->getOption('pivot-model');

        $this->defaultTable([
            (new StringWrapper('INDEX'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('TRACE'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('JOINS'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('AUTO RELATION'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
        ], $this->formatRows($relations->getFormattedTraceTableRows()))->render();

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Display table of database relationships')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }

    private function formatRows(array $traces): array
    {
        $final_rows = [];
        foreach ($traces as $index => $trace) {
            $is_auto_relation = explode(' <fg=green;options=bold>-></> ', $trace[0]);

            if (substr($is_auto_relation[0], $index % 2 === 0 ? 24 : 22) === substr($is_auto_relation[1], 0, -3)) {
                $is_auto_relation = '<fg=green;options=bold>true</>';
            } else {
                $is_auto_relation = '<fg=gray;options=bold>false</>';
            }

            $row = [$index, $trace[0], substr_count($trace[0], '->'), $is_auto_relation];

            $final_rows[] = $row;
        }

        return $final_rows;
    }
}
