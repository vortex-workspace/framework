<?php

namespace Stellar\Vortex\Helpers\StrTool\Traits;

use Ramsey\Uuid\Uuid;
use Spatie\Regex\Regex;
use Stellar\Vortex\Helpers\ArrayTool;
use Stellar\Vortex\Helpers\Typography\Enum\Encoding;

trait BooleanConditionals
{
    /**
     * @param string $string
     * @param string|array $needed
     * @return bool
     */
    public static function startWith(string $string, string|array $needed): bool
    {
        if (is_string($needed)) {
            return str_starts_with($string, $needed);
        }

        foreach ($needed as $need) {
            if (str_starts_with($string, $need)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $string
     * @param string|array $needed
     * @return bool
     */
    public static function finishWith(string $string, string|array $needed): bool
    {
        if (is_string($needed)) {
            return str_ends_with($string, $needed);
        }

        foreach ($needed as $need) {
            if (str_ends_with($string, $need)) {
                return true;
            }
        }

        return false;
    }

    public static function isJson(string $string): bool
    {
        return json_validate($string);
    }

    public static function isAscii(string $string): bool
    {
        return mb_check_encoding($string, Encoding::ASCII->value);
    }

    public static function isUuid(string $uuid): bool
    {
        return Uuid::isValid($uuid);
    }

    /**
     * @param string $string
     * @param string|string[] $needle
     * @return bool|string
     */
    public static function contains(string $string, string|array $needle): bool|string
    {
        if (is_string($needle)) {
            return str_contains($string, $needle);
        }

        foreach ($needle as $need) {
            if (str_contains($string, $need)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $string
     * @param string[] $needle
     * @return bool
     */
    public static function containsAny(string $string, array $needle): bool
    {
        foreach ($needle as $need) {
            if (str_contains($string, $need)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $string
     * @param string[] $needle
     * @return bool
     */
    public static function containsAll(string $string, array $needle): bool
    {
        foreach ($needle as $need) {
            if (!str_contains($string, $need)) {
                return false;
            }
        }

        return true;
    }

    public static function match(string $string, string $pattern): bool
    {
        return Regex::match($string, $pattern)->hasMatch();
    }

    public static function equal(string $string, string|array|int $needed, bool $strict = false): bool
    {
        if (!is_array($needed)) {
            if ($strict) {
                return $string === $needed;
            }

            return $string == $needed;
        }

        return ArrayTool::hasValue($needed, $string);
    }
}