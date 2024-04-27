<?php

namespace Stellar\Core\Cosmo\SchemaEngine;

use AllowDynamicProperties;
use Core\Adapters\Collection;

#[AllowDynamicProperties] class Table
{
    public array $columns;
    public string $name;
    public int $columns_count;
    public array $columns_name;
    public array $foreign_keys = [];
    public array $primary_keys = [];
    public array $attributes = [];
    public bool $pivot = false;
    public array $pivot_columns;

    public function __construct(string $table_name, Collection $table)
    {
        $this->name = $table_name;
        $this->columns_count = $table->count();
        $this->mountColumns($table);
        $this->setColumnsByConstraint();
        $this->checkIfIsPivot();
    }

    private function mountColumns(Collection $table): void
    {
        foreach ($table as $raw_column) {
            $column = new Column($raw_column);
            $this->columns_name[] = $column->name;
            $this->columns[] = $column;
        }
    }

    private function checkIfIsPivot(): void
    {
        $expected_foreign_key_columns = explode('_', $this->name);
        $match_count = 0;
        $unset_columns = [];

        $expected_foreign_key_columns = array_map(function ($column) {
            return "{$column}_id";
        }, $expected_foreign_key_columns);

        foreach ($this->foreign_keys as $index => $foreign_key) {
            if (in_array($foreign_key->name, $expected_foreign_key_columns)) {
                $unset_columns[] = $index;
                $this->pivot_columns[] = $foreign_key;
                $match_count++;
            }
        }

        if ($match_count === 2) {
            $this->pivot = true;
            foreach ($unset_columns as $index) {
                unset($this->foreign_keys[$index]);
            }
        } else {
            unset($this->pivot_columns);
        }
    }

    private function setColumnsByConstraint(): void
    {
        foreach ($this->columns as $column) {
            if (isset($column->referenced_table)) {
                $this->foreign_keys[] = $column;
            } else if ($column->primary_key) {
                $this->primary_keys[] = $column;
            } else {
                $this->attributes[] = $column;
            }
        }
    }
}
