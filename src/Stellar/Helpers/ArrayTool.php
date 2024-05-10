<?php

namespace Stellar\Helpers;

use ReflectionException;
use ReflectionFunction;
use Stellar\Helpers\ArrayTool\Traits\Sort;

class ArrayTool
{
    use Sort;

    /**
     * @param array $subject
     * @param mixed $delete
     * @param bool $only_first_occurrence
     * @param bool $match_type
     * @return array
     */
    public static function deleteValue(
        array $subject,
        mixed $delete,
        bool  $only_first_occurrence = false,
        bool  $match_type = true,
    ): array
    {
        if (!is_array($delete)) {
            foreach ($subject as $key => $value) {
                if ($match_type) {
                    if ($value === $delete) {
                        unset($subject[$key]);

                        if ($only_first_occurrence === true) {
                            return $subject;
                        }
                    }
                } else {
                    if ($value == $delete) {
                        unset($subject[$key]);

                        if ($only_first_occurrence === true) {
                            return $subject;
                        }
                    }
                }
            }

            return $subject;
        }

        foreach ($delete as $need) {
            if ($only_first_occurrence) {
                foreach ($subject as $key => $value) {
                    if ($match_type) {
                        if ($need === $value) {
                            unset($subject[$key]);
                        }
                    } else {
                        if ($need == $value) {
                            unset($subject[$key]);
                        }
                    }
                }

                continue;
            }

            foreach ($subject as $key => $value) {
                if ($match_type) {
                    if ($need === $value) {
                        unset($subject[$key]);
                    }
                } else {
                    if ($need == $value) {
                        unset($subject[$key]);
                    }
                }
            }
        }

        return $subject;
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param array $array
     * @param string $separator
     * @param string|null $initial_delimiter
     * @param string|null $final_delimiter
     * @return string
     */
    public static function toString(
        array   $array,
        string  $separator = ',',
        ?string $initial_delimiter = '(',
        ?string $final_delimiter = ')'
    ): string
    {
        return ($initial_delimiter ?? '') . implode($separator, $array) . ($final_delimiter ?? '');
    }

    /**
     * @param array $array
     * @param int $length
     * @param bool $preserve_keys
     * @param bool $shuffle_array
     * @return array
     */
    public static function splitInChunks(
        array $array,
        int   $length,
        bool  $preserve_keys = false,
        bool  $shuffle_array = false
    ): array
    {
        if ($shuffle_array) {
            shuffle($array);
        }

        return array_chunk($array, $length, $preserve_keys);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function shuffle(array $array): array
    {
        shuffle($array);

        return $array;
    }

    public static function getKeyValues(
        array           $array,
        int|string      $column_key,
        int|string|null $index_key = null
    ): array
    {
        return array_column($array, $column_key, $index_key);
    }

    /**
     * @param array $keys
     * @param array $values
     * @return array
     */
    public static function combine(array $keys, array $values): array
    {
        return array_combine($keys, $values);
    }

    /**
     * @param array $array
     * @param array|string|int|null $values
     * @return array
     */
    public static function valuesCounter(array $array, array|string|int|null $values = null): array
    {
        $values_counts = array_count_values($array);

        if (is_null($values)) {
            return $values_counts;
        } elseif (is_string($values) || is_int($values)) {
            if (!isset($values_counts[$values])) {
                return [];
            }

            return [(string)$values => $values_counts[(string)$values]];
        }

        $final_values_counts = [];

        foreach ($values as $value) {
            $final_values_counts[$value] = $values_counts[$value];
        }

        return $final_values_counts;
    }

    public static function last($array, ?callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }

    public static function first($array, ?callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }

            foreach ($array as $item) {
                return $item;
            }

            return value($default);
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * @param array $array
     * @return false|array
     */
    public static function setKeyEqualValue(array $array): false|array
    {
        $final_array = [];

        foreach ($array as $index => $value) {
            if (is_string($value) || is_int($value)) {
                $final_array[$value] = $value;

                continue;
            }

            return false;
        }

        return $final_array;
    }

    public static function hasValue(array $array, mixed $value, bool $strict = false): bool
    {
        return in_array($value, $array, $strict);
    }

    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    public static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof Enumerable) {
            return $array->has($key);
        }

        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        if (is_float($key)) {
            $key = (string)$key;
        }

        return array_key_exists($key, $array);
    }

    public static function map(array $array, callable $callback): array
    {
        $keys = array_keys($array);

        try {
            $items = array_map($callback, $array, $keys);
        } catch (\ArgumentCountError) {
            $items = array_map($callback, $array);
        }

        return array_combine($keys, $items);
    }

    /**
     * @param array $subject
     * @param string|callable $separator
     * @return string
     * @throws ReflectionException
     */
    public static function implode(array $subject, string|callable $separator): string
    {
        if (is_callable($separator)) {
            $final_string = '';
            $full = false;
            $function = new ReflectionFunction($separator);

            if ($function->getNumberOfParameters() === 2) {
                $full = true;
            }

            foreach ($subject as $index => $value) {
                $final_string .= $full ? $separator($index, $value) : $separator($value);
            }

            return $final_string;
        }

        return implode($separator, $subject);
    }
}
