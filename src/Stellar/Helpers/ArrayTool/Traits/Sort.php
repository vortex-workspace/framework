<?php

namespace Stellar\Helpers\ArrayTool\Traits;

use Stellar\Helpers\ArrayTool\Enum\SortMode;

trait Sort
{
    public static function sortNumerically(array $array): array
    {
        sort($array, SortMode::NUMERIC->value);

        return $array;
    }

    public static function sortRegularly(array $array): array
    {
        sort($array, SortMode::REGULAR->value);

        return $array;
    }

    public static function sortStringable(array $array): array
    {
        sort($array, SortMode::STRING->value);

        return $array;
    }

    public static function sortLocally(array $array): array
    {
        sort($array, SortMode::LOCALE_STRING->value);

        return $array;
    }

    public static function sortNaturally(array $array): array
    {
        sort($array, SortMode::NATURAL->value);

        return $array;
    }

    /**
     * @param array $array
     * @param SortMode[] $sort_modes
     * @return array
     */
    public static function customSort(array $array, array $sort_modes): array
    {
        foreach ($sort_modes as $index => $sortMode) {
            $sort_modes[$index] = $sortMode->value;
        }

        sort($array, $sort_modes);

        return $array;
    }
}