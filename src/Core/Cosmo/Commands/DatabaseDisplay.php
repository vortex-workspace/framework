<?php

namespace Stellar\Core\Cosmo\Commands;

use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\SchemaEngine\Column;
use Stellar\Core\Cosmo\SchemaEngine\SchemaMapper;
use Stellar\Core\Cosmo\SchemaEngine\Table;
use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Cosmo\Command\StringWrapper;
use Stellar\Helpers\Environment;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'DB:display',
)]
class DatabaseDisplay extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        if (empty(($schema = new SchemaMapper())->tables)) {
            $this->breakLine();
            $this->debuggerBlock('No tables found in schema "' . Environment::dbDatabase() . '".');
            $this->breakLine();

            return CommandReturnStatus::SUCCESS;
        }

        $headers = [];

        foreach (['TABLE', 'COLUMN', 'TYPE', 'DEFAULT', 'NULLABLE', 'PK', 'FK', 'UNIQUE', 'AUTO INCR.', 'CAS. ON DEL.',
                     'CAS. ON UPD.', 'MAX LEN.', 'OPTIONS'] as $header) {
            $headers[] = (new StringWrapper($header))
                ->foreground(ConsoleStyleColor::BRIGHT_GREEN)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap();
        }

        $this->defaultTable($headers, $this->mountTableRows($schema))->render();

        return CommandReturnStatus::SUCCESS;
    }

    private function mountTableRows(SchemaMapper $schema): array
    {
        $rows = [];

        /** @var Table $table */
        foreach ($schema->tables as $table) {
            foreach ($table->columns as $column) {
                $rows[] = $this->mountColumnRow($column, $table);
            }
        }

        return $rows;
    }

    private function mountColumnRow(Column $column, Table $table): array
    {
        return [
            (new StringWrapper($table->name))->foreground(ConsoleStyleColor::BRIGHT_BLUE)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->name))->foreground(ConsoleStyleColor::WHITE)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->type))->foreground(ConsoleStyleColor::WHITE)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->default ?: 'unset'))
                ->foreground($column->default ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->nullable ? 'true' : 'false'))
                ->foreground($column->nullable ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->primary_key ? 'true' : 'false'))
                ->foreground($column->primary_key ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->foreign_key ? 'true' : 'false'))
                ->foreground($column->foreign_key ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->unique ? 'true' : 'false'))
                ->foreground($column->unique ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->auto_increment ? 'true' : 'false'))
                ->foreground($column->auto_increment ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->cascade_on_delete ? 'true' : 'false'))
                ->foreground($column->cascade_on_delete ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->cascade_on_update ? 'true' : 'false'))
                ->foreground($column->cascade_on_update ? ConsoleStyleColor::YELLOW : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->max_length && $column->type !== 'set' && $column->type !== 'enum'
                ? $column->max_length : 'unset'))
                ->foreground($column->max_length && $column->type !== 'set' && $column->type !== 'enum'
                    ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
            (new StringWrapper($column->options ?: 'unset'))
                ->foreground($column->options ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                ->options([ConsoleStyleOption::BOLD])
                ->wrap(),
        ];
    }
}
