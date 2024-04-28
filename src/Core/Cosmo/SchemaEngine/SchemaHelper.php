<?php

namespace Stellar\Core\Cosmo\SchemaEngine;

use Core\Database\Query\Facades\Query;
use Stellar\Core\Cosmo\Cosmo;
use Symfony\Component\Console\Command\Command;

class SchemaHelper
{
    public static function checkIfDatabaseIsSetAndExists(Cosmo $cosmo, Command $command): bool
    {
        if (!isset($_ENV['DB_DATABASE']) || $_ENV['DB_DATABASE'] === '') {
            $cosmo->finish();
            $cosmo->failMessage("Database name not found in .env", $command);
            $cosmo->commandFail($command->getName());

            return false;
        }

        $exist_database = Query::select('information_schema.schemata', 'schema_name ')
            ->where('schema_name', $_ENV['DB_DATABASE'])->get()->count();

        if ($exist_database !== 1) {
            $cosmo->finish();
            $cosmo->failMessage('Database with name "' . $_ENV['DB_DATABASE'] . '" not found', $command);
            $cosmo->commandFail($command->getName());

            return false;
        }

        return true;
    }
}
