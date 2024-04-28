<?php

namespace Stellar\Helpers\StrTool\Traits;

trait StringCases
{
    public static function camelCase(string $string): string
    {
        return self::getInflector()->camelize(self::snakeCase($string));
    }

    public static function pascalCase(string $string): string
    {
        return self::getInflector()->classify(self::snakeCase($string));
    }

    public static function snakeCase(string $string, array|string $replaced = ['-', ' ', '_']): string
    {
        if (ctype_upper(str_replace(['_', '-', ' '], '', $string))) {
            $string = strtolower($string);
        }

        $split_string = mb_str_split(str_replace($replaced, '_', lcfirst($string)));

        foreach ($split_string as $index => $character) {
            if ($index > 0 && $split_string[$index - 1] === '_') {
                continue;
            }

            if (ctype_upper($character)) {
                $split_string[$index] = '_' . strtolower($character);
            }
        }

        return mb_strtolower(implode('', $split_string));
    }

    public static function slugCase(string $string): string
    {
        return self::getInflector()->urlize(self::snakeCase($string));
    }

    public static function kebabCase(string $string): string
    {
        return self::slugCase($string);
    }

    public static function humanReadableCase(string $string): string
    {
        return ucfirst(str_replace('-', ' ', self::slugCase($string)));
    }

    public static function titleCase(string $string): string
    {
        return ucwords(str_replace('-', ' ', self::slugCase($string)));
    }

    public static function lowerCase(string $string): string
    {
        return mb_strtolower($string);
    }

    public static function upperCase(string $string): string
    {
        return mb_strtoupper($string);
    }
}