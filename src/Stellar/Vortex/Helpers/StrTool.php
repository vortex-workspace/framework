<?php

namespace Stellar\Vortex\Helpers;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Stellar\Vortex\Facades\Faker;
use Stellar\Vortex\Facades\Faker\Enum\Locale;
use Stellar\Vortex\Helpers\StrTool\Enum\Language;
use Stellar\Vortex\Helpers\StrTool\StringMounter;
use Stellar\Vortex\Helpers\StrTool\Traits\BooleanConditionals;
use Stellar\Vortex\Helpers\StrTool\Traits\Identifiers;
use Stellar\Vortex\Helpers\StrTool\Traits\Skills;
use Stellar\Vortex\Helpers\StrTool\Traits\StringCases;
use Stellar\Vortex\Helpers\StrTool\Traits\StringTransformation;
use Stellar\Vortex\Helpers\StrTool\Traits\Substring;
use Stellar\Vortex\Settings\Enum\SettingKey;
use Stellar\Vortex\Settings\Setting;
use Stellar\Vortex\Throwable\Exceptions\Generics\BadMethodCallException;

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
                Setting::get(SettingKey::INTERNALS_LANGUAGES_INFLECTOR->getFullTrace(), Language::ENGLISH)->value
            )->build();
        }

        return self::$inflector;
    }

    public static function getFaker(?Locale $locale = null): Faker
    {
        if (!isset(self::$faker)) {
            return self::$faker = new Faker($locale ??
                Setting::get(SettingKey::INTERNALS_LANGUAGES_FAKER->getFullTrace(), Locale::ENGLISH_US));
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