<?php

namespace Stellar\Helpers;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Stellar\Facades\Faker;
use Stellar\Facades\Faker\Enum\Locale;
use Stellar\Helpers\StrTool\Enum\Language;
use Stellar\Helpers\StrTool\StringMounter;
use Stellar\Helpers\StrTool\Traits\BooleanConditionals;
use Stellar\Helpers\StrTool\Traits\Identifiers;
use Stellar\Helpers\StrTool\Traits\Skills;
use Stellar\Helpers\StrTool\Traits\StringCases;
use Stellar\Helpers\StrTool\Traits\StringTransformation;
use Stellar\Helpers\StrTool\Traits\Substring;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;

/**
 * @mixin  Inflector
 */
class StrTool
{
    use BooleanConditionals;
    use Identifiers;
    use Skills;
    use StringCases;
    use StringTransformation;
    use Substring;

    protected static Inflector $inflector;
    protected static Faker $faker;

    protected static function getInflector(?Language $language = null): Inflector
    {
        if (!isset(self::$inflector)) {
            return self::$inflector = InflectorFactory::createForLanguage(
                Setting::get(SettingKey::InternalLanguagesInflector->getFullTrace(), Language::ENGLISH)->value
            )->build();
        }

        return self::$inflector;
    }

    public static function getFaker(?Locale $locale = null): Faker
    {
        if (!isset(self::$faker)) {
            return self::$faker = new Faker($locale ??
                Setting::get(SettingKey::InternalLanguagesFaker->getFullTrace(), Locale::ENGLISH_US));
        }

        return self::$faker;
    }

    private function __construct(private string $string)
    {
    }

    public static function of(string $string): StringMounter
    {
        return new StringMounter($string);
    }
}