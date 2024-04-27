<?php

namespace Stellar\Vortex\Request\Traits;

use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Helpers\Typography\Enum\Operator;
use Stellar\Vortex\Helpers\Typography\Enum\Typography;

trait Setters
{
    private static function setQueryParameters(): void
    {
        if (!isset($_SERVER['QUERY_STRING'])) {
            self::$query_parameters = [];

            return;
        }

        $query_strings = explode(Typography::Ampersand->value, $_SERVER['QUERY_STRING']);
        $parameters = [];

        foreach ($query_strings as $query_parameter) {
            if ($query_parameter === Typography::EmptyString->value) {
                continue;
            }

            if (!StrTool::contains($query_parameter, Operator::Equal->value)) {
                $parameters[$query_parameter] = true;

                continue;
            }

            $exploded_parameter = explode(Typography::EqualOperator->value, $query_parameter);
            $parameters[$exploded_parameter[0]] = $exploded_parameter[1];
        }

        self::$query_parameters = $parameters;
    }

    private static function setAttributes(): void
    {
        self::$attributes = array_merge($_POST ?? [], $_GET ?? []);
    }

    private static function setCookies(): void
    {

        self::$cookies = $_COOKIE ?? [];
    }

    private static function setFiles(): void
    {
        self::$files = $_FILES ?? [];
    }

    private static function setAll(): void
    {
        self::$all = array_merge(self::$cookies, self::$files, self::$query_parameters, self::$attributes);
    }
}
