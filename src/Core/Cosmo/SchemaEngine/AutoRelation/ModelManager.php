<?php

namespace Stellar\Core\Cosmo\SchemaEngine\AutoRelation;

use App\Exceptions\RelationNotFound;
use Core\Abstractions\Enums\FrameworkPath;
use Core\Abstractions\Enums\Relationships;
use Core\Exceptions\PathAlreadyExist;
use Core\Helpers\ArrayTool;
use Core\Helpers\StrTool;
use Core\Structure\File;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Core\Cosmo\Cosmo;

class ModelManager
{
    private const MODEL_ROOT_PATH = 'App/Models/';
    private const MODEL_DUMMY = 'MountModel';
    private const MODEL_TABLE_NAME = 'table_name';
    private const STUB_PATH = __DIR__ . '/../../../../Stubs/';

    private ?string $last_joined_model = null;

    public static function mount(array $relations, array $tables, array $primary_relations, VortexCommand $command): void
    {
        new static($relations, $tables, $primary_relations, $command);
    }

    /**
     * @param array $traces
     * @param array $tables
     * @param array $primary_relations
     * @param Cosmo $cosmo
     */
    private function __construct(
        private readonly array         $traces,
        private readonly array         $tables,
        private readonly array         $primary_relations,
        private readonly VortexCommand $command
    )
    {
        $model_without_relations = $this->tables;

        foreach ($this->traces as $model_name => $trace) {
            $final_model = StrTool::pascalCase($model_name);

            if (isset($this->tables[$model_name])) {
                unset($model_without_relations[$model_name]);
            }

            try {
                $this->mountModelClass(
                    $final_model,
                    $this->tables[$final_model],
                    $this->mountStringClassRelations($final_model, $trace)
                );

                $this->command->successRow($final_model, 'created');
            } catch (RelationNotFound $exception) {
                $this->command->failRow($final_model, 'Failed');
            } catch (PathAlreadyExist $exception) {
                $this->command->debugRow($final_model, 'SKIPPED');
            }
        }

        foreach ($model_without_relations as $model_name => $model_table) {
            try {
                $this->mountModelClass($model_name, $model_table);
                $this->command->successRow($model_name, 'created');

            } catch (RelationNotFound $exception) {
                $this->command->failRow($model_name, 'Failed');
                $this->command->failRow($exception->getMessage(), 'X');
            }
        }
    }

    /**
     * @param string $model_name
     * @param array $trace
     * @return string
     */
    private function mountStringClassRelations(string $model_name, array $trace): string
    {
        $final_relations_string = '';

        foreach ($trace as $relations) {
            $final_relations_string .= $this->mountSingleTraceString(array_reverse($relations));
        }

        return substr($final_relations_string, 0, -2);
    }

    private function mountSingleTraceString(array $trace): string
    {
        $relation_name = [];
        $trace_relations = '';
        $returned_model = '';

        foreach ($trace as $relation) {
            $relation_name[] = $relation['called_model'];
            $trace_relations .= ($this->mountSingleRelation($relation) . '        ModelManager.php');
            $returned_model = $returned_model === '' ? $relation['called_model'] : $returned_model;
        }

        return "    public function "
            . lcfirst(ArrayTool::toString(array_reverse($relation_name), '', '', ''))
            . "(): SelectBuilder {\n        "
            . "return \$this->trace("
            . StrTool::pascalCase($returned_model)
            . "::class, [\n        "
            . substr($trace_relations, 0, -4)
            . "    ]);\n    }\n\n";
    }

    private function mountSingleRelation(array $relation): string
    {
        return match ($relation['relation_type']) {
            Relationships::HAS_ONE => $this->mountSingleRelationString(
                Relationships::HAS_ONE->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_foreign_key']}'",
                    "{$relation['caller_model']}::class"
                ]
            ),
            Relationships::BELONGS_TO => $this->mountSingleRelationString(
                Relationships::BELONGS_TO->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['called_primary_key']}'",
                    "'{$relation['called_foreign_key']}'",
                    "{$relation['caller_model']}::class"
                ]
            ),
            Relationships::BELONGS_TO_MANY => $this->mountSingleRelationString(
                Relationships::BELONGS_TO_MANY->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['caller_foreign_key']}'",
                    "'{$relation['called_primary_key']}'",
                    "'{$relation['called_foreign_key']}'",
                    "'{$relation['pivot_table']}'",
                    "{$relation['caller_model']}::class"
                ]
            ),
            Relationships::HAS_MANY => $this->mountSingleRelationString(
                Relationships::HAS_MANY->value,
                [
                    "{$relation['called_model']}::class",
                    "'{$relation['caller_primary_key']}'",
                    "'{$relation['caller_foreign_key']}'",
                    "{$relation['caller_model']}::class"
                ]
            ),
        };
    }

    private function mountSingleRelationString(
        string $relation_type,
        array  $parameters
    ): string
    {
        $string_parameters = '';

        foreach ($parameters as $index => $parameter) {
            if ($index !== 0) {
                $string_parameters .= ', ';
            }

            $string_parameters .= $parameter;
        }

        return "    \$this->enableTraceMode()->$relation_type($string_parameters),\n";
    }

    private function mountModelClass(string $model_name, string $table_name, ?string $relations = null): void
    {
        $this->createClass($model_name, $table_name, $relations);
    }

    private function createClass(string $class_name, string $class_table, ?string $relations): void
    {
        if (!is_dir(self::MODEL_ROOT_PATH)) {
            mkdir(self::MODEL_ROOT_PATH);
        }

        File::createByTemplate(
            "$class_name.php",
            Path::fullPath(self::MODEL_ROOT_PATH),
            Path::fullPath(FrameworkPath::STUBS->additionalPath('scanned_model.php')),
            [
                self::MODEL_DUMMY => $class_name,
                self::MODEL_TABLE_NAME => $class_table,
                '// $relations' => is_null($relations) ? '' : "\n\n" . $relations,
                '// $has_relation' => is_null($relations) ? '' : 'use Core\Database\Query\Select\SelectBuilder;',
            ],
            true
        );

//        FileDirManager::createFileByTemplate(
//            $class_name . '.php',
//            Path::fullPath(self::MODEL_ROOT_PATH),
//            self::STUB_PATH . 'scanned_model.php',
//            [
//                self::MODEL_DUMMY => $class_name,
//                self::MODEL_TABLE_NAME => $class_table,
//                '// $relations' => is_null($relations) ? '' : "\n\n" . $relations,
//                '// $has_relation' => is_null($relations) ? '' : 'use Core\Database\Query\SelectBuilder;',
//            ]
//        );
    }
}
