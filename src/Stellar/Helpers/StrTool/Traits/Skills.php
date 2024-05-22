<?php

namespace Stellar\Helpers\StrTool\Traits;

use Faker\Factory;

trait Skills
{
    public static function length(string $string): int
    {
        return strlen($string);
    }

    public static function wordCount(string $string): int
    {
        return str_word_count($string);
    }

    public static function split(string $string, int $split_max_length = 1): array
    {
        return str_split($string, $split_max_length);
    }

    public static function randomText(int $count = 20): string
    {
        if ($count < 5) {
            return self::lowerCase(self::substring(Factory::create()->text(5), 0, $count));
        }

        return self::lowerCase(Factory::create()->text($count));
    }

    public static function randomCharacter(): string
    {
        return substr(md5(mt_rand()), 0, 1);
    }
}