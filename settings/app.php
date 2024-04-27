<?php

use Stellar\Vortex\Languages\Enum\Language;

return [
    /**
     * Set the default application language
     * 'default_language' => Stellar\Core\Languages\Enum\Languages|string
     */
    'default_language' => Language::ENGLISH_UNITED_STATES,

    /**
     * Set default application date and date time formats
     * 'default_date_format' => 'Y-m-d',
     * 'default_date_time_format' => 'Y-m-d h:i:s'
     */
    'default_date_format' => 'Y-m-d',
    'default_date_time_format' => 'Y-m-d h:i:s',

    /**
     * Enable application to preload all settings, this is not cache.
     * 'preload_settings' => bool
     * true - Application will preload all settings,
     * false - Application will load settings on demand
     */
    'preload_settings' => false,
    'default_request' => \Stellar\Vortex\Request::class,
    'adapters' => [
        \Stellar\Vortex\Adapters\VortexCore::class
    ],
];