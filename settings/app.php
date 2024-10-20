<?php

return [
    /**
     * Set the default application language
     * 'default_language' => Stellar\Core\Languages\Enum\Languages|string
     */
    'default_language' => \Stellar\Languages\Enum\Language::ENGLISH_UNITED_STATES,

    /**
     * Set default application date and date time formats
     * 'default_date_format' => 'Y-m-d',
     * 'default_date_time_format' => 'Y-m-d h:i:s'
     */
    'default_date_format' => 'Y-m-d',
    'default_date_time_format' => 'Y-m-d h:i:s',
    'providers' => [
        \Stellar\Providers\VortexCore::class,
        \Stellar\Storage\StorageProvider::class,
    ],
    'default_request_class' => \Stellar\Request::class,
    /**
     * List all Gateways that need overwrite in application
     */
    'gateways' => [],
    /**
     * List services to be used in application.
     * Warning: Some services are required, if any be provided, the default services will be used.
     */
    'services' => [
        \Stellar\Services\Route\ControllerRouteMatchService::class,
        \Stellar\Services\Route\RouteFinderService::class,
    ],
    'injections' => [

    ]
];