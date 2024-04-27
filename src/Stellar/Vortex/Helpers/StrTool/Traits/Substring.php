<?php

namespace Stellar\Vortex\Helpers\StrTool\Traits;

trait Substring
{
    /**
     * @param string $string
     * @param string $separator
     * @param bool $with_separator
     * @param int $ignore_first_separators
     * @return string|false
     */
    public static function after(
        string $string,
        string $separator,
        bool   $with_separator = false,
        int    $ignore_first_separators = 0
    ): string|false
    {
        if (!str_contains($string, $separator)) {
            return $string;
        }

        $exploded_string = explode($separator, $string);

        for ($i = 0; $i <= $ignore_first_separators; $i++) {
            unset($exploded_string[$i]);
        }

        if (count($exploded_string) === 0) {
            return '';
        }

        $exploded_string = implode($separator, $exploded_string);

        return $with_separator ? $separator . $exploded_string : $exploded_string;
    }

    public static function afterLast(string $string, string $separator, bool $with_separator = false)
    {
        if (!str_contains($string, $separator)) {
            return $string;
        }

        /** @var false|array $exploded_string */
        $exploded_string = explode($separator, $string);

        if (empty($exploded_string) || count($exploded_string) < 1) {
            return $string;
        }

        return $exploded_string[count($exploded_string) - 1];
    }

    public static function beforeLast()
    {

    }

    public static function before(
        string $string,
        string $separator,
        bool   $with_separator = false,
        int    $ignore_first_separators = 0
    ): string|false
    {
        if (!str_contains($string, $separator)) {
            return $string;
        }

        $exploded_string = explode($separator, $string);

        for ($i = count($exploded_string); $i > $ignore_first_separators; $i--) {
            unset($exploded_string[$i]);
        }

        $exploded_string = implode($separator, $exploded_string);

        return $with_separator ? $exploded_string . $separator : $exploded_string;
    }

    /**
     * @param string $string
     * @param string|array $needed
     * @return string
     */
    public static function removeIfStartWith(string $string, string|array $needed): string
    {
        if (is_string($needed)) {
            return str_starts_with($string, $needed) ? substr($string, strlen($needed)) : $string;
        }

        foreach ($needed as $need) {
            if (str_starts_with($string, $need)) {
                return substr($string, strlen($need));
            }
        }

        return $string;
    }

    /**
     * @param string $string
     * @param string|array $needed
     * @return string
     */
    public static function removeIfEndWith(string $string, string|array $needed): string
    {
        if (is_string($needed)) {
            return str_ends_with($string, $needed)
                ? substr($string, 0, strlen($string) - strlen($needed))
                : $string;
        }

        foreach ($needed as $need) {
            if (str_ends_with($string, $need)) {
                return substr($string, 0, strlen($string) - strlen($need));
            }
        }

        return $string;
    }

    /**
     * @param string $string
     * @param string|array $needed
     * @return string
     */
    public static function removeIfStartAndFinishWith(string $string, string|array $needed): string
    {
        return self::removeIfEndWith(self::removeIfStartWith($string, $needed), $needed);
    }

    /**
     * @param string $string
     * @param int $start
     * @param int|null $length
     * @return string
     */
    public static function substring(string $string, int $start, ?int $length = null): string
    {
        return substr($string, $start, $length);
    }
}