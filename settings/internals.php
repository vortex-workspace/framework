<?php

use Stellar\Vortex\Facades\Faker\Enum\Locale;
use Stellar\Vortex\Helpers\StrTool\Enum\Language as InflectorLanguage;

return [
    'languages' => [
        /**
         * Set the faker language from available locales
         * 'faker_locale' => Stellar\Core\Facades\Faker\Enum\Locale
         */
        'faker' => Locale::ENGLISH_US,

        /**
         *  Set Inflector language to be used on StrTool Helper
         * 'inflector_language' => Stellar\Core\Helpers\StrTool\Enum\Languages as InflectorLanguage
         */
        'inflector' => InflectorLanguage::ENGLISH,
    ],
];