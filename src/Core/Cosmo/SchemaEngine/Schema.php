<?php

namespace Stellar\Core\Cosmo\SchemaEngine;

use Core\Database\Query\Facades\Query;
use Core\Exceptions\SchemaNotFound;
use Core\Exceptions\SchemaNotSet;
use Stellar\Helpers\Environment;

class Schema
{
    public static function isSet(): bool
    {
        return Environment::dbDatabase() !== null;
    }

    /**
     * @return void
     * @throws SchemaNotFound
     * @throws SchemaNotSet
     */
    public static function existOrFail(): void
    {
        if (!self::isSet()) {
            throw new SchemaNotSet();
        }

        if (Query::select('information_schema.schemata', 'schema_name')
                ->where('schema_name', Environment::dbDatabase())
                ->get()
                ->count() !== 1) {

            throw new SchemaNotFound();
        }
    }

    public static function exist(): bool
    {
        if (!self::isSet()) {
            return false;
        }

        if (Query::select('information_schema.schemata', 'schema_name')
                ->where('schema_name', $_ENV['DB_DATABASE'])
                ->get()
                ->count() !== 1) {

            return false;
        }

        return true;
    }

    public static function notExist(): bool
    {
        return !self::exist();
    }
}
