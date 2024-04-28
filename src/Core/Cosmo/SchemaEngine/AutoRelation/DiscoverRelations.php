<?php

namespace Stellar\Core\Cosmo\SchemaEngine\AutoRelation;

use Core\Abstractions\Enums\Relationships;
use Core\Exceptions\SchemaHaveNoTables;
use Core\Exceptions\SchemaTablesHaveNoRelations;
use Core\Helpers\StrTool;
use Exception;
use Stellar\Core\Cosmo\SchemaEngine\Column;
use Stellar\Core\Cosmo\SchemaEngine\RelationTree;
use Stellar\Core\Cosmo\SchemaEngine\SchemaMapper;
use Stellar\Core\Cosmo\SchemaEngine\Table;

class DiscoverRelations
{
    private SchemaMapper $schema;
    private array $final_relationships = [];
    private array $models_tables = [];
    private bool $has_relations = false;

    public function __construct(
        private readonly bool $with_pivot_model = true,
        private readonly bool $with_test = true)
    {
        $this->schema = new SchemaMapper();

        if (empty($this->schema->tables)) {
            throw new SchemaHaveNoTables();
        }
    }

    public function setRelations(): static
    {
        $this->models_tables = [];

        foreach ($this->schema->tables as $table) {
            if ($table->pivot) {
                if ($this->with_pivot_model) {
                    $this->models_tables[StrTool::pascalCase($table->name)] = $table->name;
                }

                $this->resolvePivotRelations($table);

                continue;
            }

            $this->models_tables[StrTool::pascalCase(StrTool::singularize($table->name))] = $table->name;

            if (empty($table->foreign_keys)) {
                if (empty($this->final_relationships[StrTool::pascalCase(StrTool::singularize($table->name))])) {
                    $this->final_relationships[StrTool::pascalCase(StrTool::singularize($table->name))] = [];
                }

                continue;
            }

            foreach ($table->foreign_keys as $foreign_key) {
                $this->has_relations = true;

                /** @var Column $foreign_key */
                $this->resolveRelation($table, $foreign_key);
            }
        }

        if (!$this->has_relations) {
            throw new SchemaTablesHaveNoRelations();
        }

        return $this;
    }

    private function resolvePivotRelations(Table $pivotTable): void
    {
        $second_model = $this->getModelNameByTable(substr($pivotTable->pivot_columns[0]->name, 0, -3));
        $first_model = $this->getModelNameByTable(substr($pivotTable->pivot_columns[1]->name, 0, -3));

        $this->final_relationships[$first_model][] = [
            'called_model' => $second_model,
            'caller_primary_key' => $pivotTable->pivot_columns[1]->referenced_column,
            'caller_foreign_key' => $pivotTable->pivot_columns[1]->name,
            'pivot_table' => $pivotTable->name,
            'called_primary_key' => $pivotTable->pivot_columns[0]->referenced_column,
            'called_foreign_key' => $pivotTable->pivot_columns[0]->name,
            'relation_type' => Relationships::BELONGS_TO_MANY,
            'id' => $this->generateUniqueId(),
        ];

        $this->final_relationships[$second_model][] = [
            'called_model' => $first_model,
            'caller_primary_key' => $pivotTable->pivot_columns[0]->referenced_column,
            'caller_foreign_key' => $pivotTable->pivot_columns[0]->name,
            'pivot_table' => $pivotTable->name,
            'called_primary_key' => $pivotTable->pivot_columns[1]->referenced_column,
            'called_foreign_key' => $pivotTable->pivot_columns[1]->name,
            'relation_type' => Relationships::BELONGS_TO_MANY,
            'id' => $this->generateUniqueId(),
        ];

        if ($this->with_pivot_model) {
            $pivot_model = StrTool::pascalCase($pivotTable->name);

            $this->final_relationships[$pivot_model][] = [
                'called_model' => $first_model,
                'caller_primary_key' => $pivotTable->primary_keys[0]->name ?? 'id',
                'called_primary_key' => $this->schema->tables[$pivotTable->pivot_columns[0]->referenced_table]->primary_key[0] ?? 'id',
                'called_foreign_key' => $pivotTable->pivot_columns[0]->name,
                'relation_type' => Relationships::BELONGS_TO,
                'id' => $this->generateUniqueId(),
            ];

            $this->final_relationships[$pivot_model][] = [
                'called_model' => $second_model,
                'caller_primary_key' => $pivotTable->primary_keys[1]->name ?? 'id',
                'called_primary_key' => $this->schema->tables[$pivotTable->pivot_columns[1]->referenced_table]->primary_key[0] ?? 'id',
                'called_foreign_key' => $pivotTable->pivot_columns[1]->name,
                'relation_type' => Relationships::BELONGS_TO,
                'id' => $this->generateUniqueId(),
            ];
        }
    }

    private function resolveRelation(Table $table, Column $column): void
    {
        $related_model = $this->getModelNameByTable($table->name);
        $main_model = $this->getModelNameByTable($column->referenced_table);

        if ($column->referenced_table === $table->name) {
            $this->final_relationships[$main_model][] = [
                'called_model' => $main_model,
                'caller_foreign_key' => $column->name,
                'relation_type' => Relationships::HAS_ONE,
                'auto_relation' => true,
                'id' => $this->generateUniqueId(),
            ];

            return;
        }

        if ($column->unique) {
            $this->final_relationships[$related_model][] = [
                'called_model' => $main_model,
                'caller_primary_key' => $table->primary_keys[1]->name ?? 'id',
                'called_primary_key' => $column->referenced_column,
                'called_foreign_key' => $column->name,
                'relation_type' => Relationships::BELONGS_TO,
                'id' => $this->generateUniqueId(),
            ];

            $this->final_relationships[$main_model][] = [
                'called_model' => $related_model,
                'caller_foreign_key' => $column->name,
                'relation_type' => Relationships::HAS_ONE,
                'id' => $this->generateUniqueId(),
            ];

            return;
        }

        $this->final_relationships[$related_model][] = [
            'called_model' => $main_model,
            'caller_primary_key' => $table->primary_keys[0]->name ?? 'id',
            'called_primary_key' => $column->referenced_column,
            'called_foreign_key' => $column->name,
            'relation_type' => Relationships::BELONGS_TO,
            'id' => $this->generateUniqueId(),
        ];

        $this->final_relationships[$main_model][] = [
            'called_model' => $related_model,
            'caller_primary_key' => 'id',
            'caller_foreign_key' => $column->name,
            'relation_type' => Relationships::HAS_MANY,
            'id' => $this->generateUniqueId(),
        ];
    }

    public function setTraceRelations(): RelationTree
    {
        return new RelationTree($this->final_relationships, $this->models_tables);
    }

    private function getModelNameByTable(string $table_name): string
    {
        return StrTool::pascalCase(StrTool::singularize($table_name));
    }

    /**
     * @return string
     * @throws Exception
     */
    private function generateUniqueId(): string
    {
        return random_bytes(10);
    }

    public function getPrimaryRelations(): array
    {
        return $this->final_relationships;
    }
}
