<?php

namespace Stellar\Helpers\StrTool\Traits;

use Stellar\Helpers\StrTool\Enum\Language;

trait StringTransformation
{
    /**
     * @param string $string
     * @param string $force_string
     * @return string
     */
    public static function forceFinishWith(string $string, string $force_string): string
    {
        return str_ends_with($string, $force_string) ? $string : $string . $force_string;
    }

    /**
     * @param string $string
     * @param string $force_string
     * @return string
     */
    public static function forceStartWith(string $string, string $force_string): string
    {
        return str_starts_with($string, $force_string) ? $string : $force_string . $string;
    }

    /**
     * @param string $string
     * @param string $force_string
     * @return string
     */
    public static function forceWrapWith(string $string, string $force_string): string
    {
        return self::forceFinishWith(self::forceStartWith($string, $force_string), $force_string);
    }

    /**
     * @param string $string
     * @param array $delimiters
     * @param Language|null $language
     * @return string
     */
    public static function capitalize(
        string    $string,
        array     $delimiters = [" ", "\n", "\t", "\r", "\0", "\x", "0B", "-"],
        ?Language $language = null
    ): string
    {
        return self::getInflector($language)->capitalize($string, implode('', $delimiters));
    }

    /**
     * @param string $string
     * @param Language|null $language
     * @return string
     */
    public static function pluralize(string $string, ?Language $language = null): string
    {
        return self::getInflector($language)->pluralize($string);
    }

    /**
     * @param string $string
     * @param Language|null $language
     * @return string
     */
    public static function singularize(string $string, ?Language $language = null): string
    {
        return self::getInflector($language)->pluralize($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function unaccent(string $string): string
    {
        return self::getInflector()->unaccent($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function absoluteUpperFistLetter(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    /**
     * @param string $string
     * @return string
     */
    public static function firstLetterUppercase(string $string): string
    {
        return ucfirst($string);
    }

    public static function between(string $string, string $start, string $end): string
    {
        $string = ' ' . $string;
        $initial = strpos($string, $start);

        if ($initial == 0) {
            return '';
        }

        $initial += strlen($start);
        $len = strpos($string, $end, $initial) - $initial;

        return substr($string, $initial, $len);
    }

    public static function reverse(string $string): string
    {
        return strrev($string);
    }

    public static function upperFirst(string $string): string
    {
        return ucfirst($string);
    }

    public static function lowerFirst(string $string): string
    {
        return lcfirst($string);
    }

    /**
     * @param array|string $subject
     * @param array|string $search
     * @param array|string $replace
     * @param int $limit
     * @return string[]|array|string|null
     */
    public static function replace(
        array|string $subject,
        array|string $search,
        array|string $replace,
        int          $limit = -1
    ): array|string|null
    {
        return preg_replace('/' . preg_quote($search, '/') . '/', $replace, $subject, $limit);
    }

    /**
     * @param array|string $subject
     * @param array|string $search
     * @param array|string $replace
     * @return string[]|array|string|null
     */
    public static function replaceFirst(
        array|string $subject,
        array|string $search,
        array|string $replace
    ): string|array|null
    {
        return self::replace($subject, $search, $replace, 1);
    }
}