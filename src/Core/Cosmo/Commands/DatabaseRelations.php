<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Helpers\StrTool;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\SchemaEngine\AutoRelation\DiscoverRelations;
use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\StringWrapper;
use Stellar\Helpers\Environment;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'DB:relations',
)]
class DatabaseRelations extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        $with_pivot_model = $this->input->getOption('pivot-model');

        if (empty($relations = (new DiscoverRelations())->setRelations()->getPrimaryRelations())) {
            $this->breakLine();
            $this->debuggerBlock('No tables found in schema "' . Environment::dbDatabase() . '".');
            $this->breakLine();

            return CommandReturnStatus::SUCCESS;
        }

        $this->defaultTable([
            (new StringWrapper('CALLER MODEL'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('CALLED MODEL'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('RELATION TYPE'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('CALLER PK'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('CALLER FK'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('PIVOT TABLE'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('CALLED PK'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
            (new StringWrapper('CALLED FK'))->foreground(ConsoleStyleColor::GREEN)->wrap(),
        ])->setRows($this->mountTableRows($relations))->render();

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Display table of database relationships')
            ->addOption('pivot-model', 'p')
            ->addOption('test', 't');
    }

    private function mountTableRows(array $model_relations): array
    {
        $rows = [];

        foreach ($model_relations as $model_name => $relations) {
            foreach ($relations as $relation) {
                $rows[] = $this->mountColumnRow($relation, $model_name);
            }
        }

        return $rows;
    }

    private function mountColumnRow(array $relation, string $model_name): array
    {
        return [
            (new StringWrapper(StrTool::firstLetterUppercase($model_name)))
                ->foreground(ConsoleStyleColor::BRIGHT_BLUE)
                ->wrap(),
            (new StringWrapper(isset($relation['called_model']) ? StrTool::firstLetterUppercase($relation['called_model']) : 'unset'))
                ->foreground(isset($relation['called_model']) ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper(isset($relation['relation_type']) ? $relation['relation_type']->value : 'unset'))
                ->foreground(isset($relation['relation_type']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper($relation['caller_primary_key'] ?? 'unset'))
                ->foreground(isset($relation['caller_primary_key']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper($relation['caller_foreign_key'] ?? 'unset'))
                ->foreground(isset($relation['caller_foreign_key']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper($relation['pivot_table'] ?? 'unset'))
                ->foreground(isset($relation['pivot_table']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper($relation['called_primary_key'] ?? 'unset'))
                ->foreground(isset($relation['called_primary_key']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
            (new StringWrapper($relation['called_foreign_key'] ?? 'unset'))
                ->foreground(isset($relation['called_foreign_key']) ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->wrap(),
        ];
    }
}
