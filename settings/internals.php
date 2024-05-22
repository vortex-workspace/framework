<?php

use Stellar\Cryptography\Enums\PasswordAlgorithm;
use Stellar\Facades\Faker\Enum\Locale;
use Stellar\Helpers\StrTool\Enum\Language as InflectorLanguage;

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
    'hash' => [
        'password' => [
            'algorithm' => PasswordAlgorithm::Default,
            'bcrypt' => [
                'cost' => PASSWORD_BCRYPT_DEFAULT_COST,
            ],
            'argon2i' => [
                'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
            ],
            'argon2id' => [
                'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
            ]
        ]
    ]
];