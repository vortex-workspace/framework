<?php

namespace Stellar\Helpers\ArrayTool\Traits;

use Stellar\Helpers\ArrayTool\Enum\SortMode;

trait SortByKey
{
    public static function sortNumericallyByKey(array &$array): array
    {
        ksort($array, SortMode::NUMERIC->value);

        return $array;
    }

    public static function sortRegularlyByKey(array &$array): array
    {
        ksort($array);

        return $array;
    }

    public static function sortStringableByKey(array &$array): array
    {
        ksort($array, SortMode::STRING->value);

        return $array;
    }

    public static function sortLocallyByKey(array &$array): array
    {
        ksort($array, SortMode::LOCALE_STRING->value);

        return $array;
    }

    public static function sortNaturallyByKey(array &$array): array
    {
        ksort($array, SortMode::NATURAL->value);

        return $array;
    }

    /**
     * @param array $array
     * @param SortMode[] $sort_modes
     * @return array
     */
    public static function customSortByKey(array &$array, array $sort_modes): array
    {
        foreach ($sort_modes as $index => $sortMode) {
            $sort_modes[$index] = $sortMode->value;
        }

        ksort($array, $sort_modes);

        return $array;
    }
}