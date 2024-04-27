<?php

return [
    'query' => [
        /**
         * Enable request to set only defined route query parameters.
         * 'strict_mode' => bool
         * true - Filter GET parameters from URL query with only defined on routes.
         * false - Permit all query parameters to be set on Request.
         * Default - false
         */
        'strict_mode' => false,
    ],
    'paths' => [
        /**
         * Enable request to set only defined route query parameters.
         * 'overwrite' => bool
         * true - Filter GET parameters from URL query with only defined on routes.
         * false - Permit all query parameters to be set on Request.
         * Default - true
         */
        'overwrite' => true,
    ],
    /**
     * Used to prefix and group custom routes for better organization.
     * 'custom_route_files' => [
     *      'blog' => [
     *          'use_prefix' => true,
     *          'prefix' => 'blog'
     *      ],
     *      'ecommerce',
     * ]
     * The name of route files can finish with .php or not.
     */
    'custom_route_files' => [
        'api' => [
            'use_prefix' => true,
            'prefix' => 'api',
        ],
        'web' => [
            'use_prefix' => true,
            'prefix' => 'aaa',
        ],
        'ecommerce' => [
            'use_prefix' => false,
            'prefix' => 'eco',
        ]
    ]
];