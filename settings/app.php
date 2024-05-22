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
    'default_request' => \Stellar\Vortex\Request::class,
    'providers' => [
        \Stellar\Vortex\Providers\VortexCore::class
    ],
];