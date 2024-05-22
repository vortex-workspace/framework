<?php

namespace Stellar\Core\Cosmo\SchemaEngine;

class Column
{
    private array $parameters;
    public string $name;
    public string $type;
    public ?int $max_length;
    public bool $unique = false;
    public bool $primary_key = false;
    public bool $foreign_key = false;
    public string|null|int $default = null;
    public bool $nullable = true;
    public bool $auto_increment = false;
    public string $referenced_table;
    public string $referenced_column;
    public bool $cascade_on_delete = false;
    public bool $cascade_on_update = false;
    public ?string $options = null;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->setName();
        $this->setType();
        $this->setConstraints();
        $this->setForeignKey();
        unset($this->parameters);
    }

    private function setName(): void
    {
        $this->name = $this->parameters['columns.COLUMN_NAME'];
    }

    private function setType(): void
    {
        $this->type = $this->parameters['columns.DATA_TYPE'];
        $this->max_length = $this->parameters['columns.CHARACTER_MAXIMUM_LENGTH'] ?? null;

        if ($this->type === 'enum' || $this->type === 'set') {
            $options = str_replace($this->type . '(', '', $this->parameters['columns.COLUMN_TYPE']);
            $this->options = substr($options, 0, -1);
        }
    }

    private function setConstraints(): void
    {
        match ($this->parameters['columns.COLUMN_KEY']) {
            'UNI' => $this->unique = true,
            'PRI' => $this->primary_key = true,
            default => '',
        };

        if ($default = $this->parameters['columns.COLUMN_DEFAULT']) {
            $this->default = $default;
        }

        if ($auto_increment = $this->parameters['columns.EXTRA']) {
            if ($auto_increment === 'auto_increment') {
                $this->auto_increment = true;
            }
        }

        if ($this->parameters['columns.IS_NULLABLE'] === 'NO') {
            $this->nullable = false;
        }

        if ($this->parameters['REFERENTIAL_CONSTRAINTS.UPDATE_RULE'] === 'CASCADE') {
            $this->cascade_on_update = true;
        }

        if ($this->parameters['REFERENTIAL_CONSTRAINTS.DELETE_RULE'] === 'CASCADE') {
            $this->cascade_on_delete = true;
        }
    }

    private function setForeignKey(): void
    {
        if ($this->parameters['TABLE_CONSTRAINTS.CONSTRAINT_TYPE'] === 'FOREIGN KEY') {
            $this->foreign_key = true;
            $this->referenced_table = $this->parameters['KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME'];
            $this->referenced_column = $this->parameters['KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME'];
        }
    }
}
