<?php

return [
    'default' => 'local',
    'drives' => [
        'local' => [
            'type' => '',
            // Set a custom name for creating a symlink directory. This name will be used to access storage routes.
            'custom_public_directory' => '',
            'partitions' => [
                'public' => true,
                'private' => true,
            ],
        ],
        'api' => [
            'type' => '',
            'partitions' => [
                'public' => true,
                'private' => true,
            ],
        ],
        's3' => [
            'type' => '',
            'access_key' => env('AWS_ACCESS_KEY'),
            'secret_key' => env('AWS_SECRET_KEY'),
            'region' => env('AWS_REGION', 'eu-west-1'),
            'endpoint' => env('AWS_ENDPOINT', ''),
            'bucket' => env('AWS_STORAGE_BUCKET'),
        ]
    ],
];