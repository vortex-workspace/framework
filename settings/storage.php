<?php

use AwsStorage\S3StorageProvider;

return [
    'default' => 'local',
    'drives' => [
        'local' => [
            'partitions' => [
                'public' => true,
                'private' => true,
            ],
            'exception_mode' => true,
        ],
        'api' => [
            'partitions' => [
                'public' => true,
                'private' => true,
            ],
            'exception_mode' => true,
        ],
        's3' => [
            'access_key' => env(S3StorageProvider::ENVIRONMENT_AWS_ACCESS_KEY),
            'secret_key' => env(S3StorageProvider::ENVIRONMENT_AWS_SECRET_KEY),
            'region' => env(S3StorageProvider::ENVIRONMENT_AWS_REGION),
            'endpoint' => env(S3StorageProvider::ENVIRONMENT_AWS_ENDPOINT),
            'bucket' => env(S3StorageProvider::ENVIRONMENT_AWS_STORAGE_BUCKET),
            'use_ssl' => env(S3StorageProvider::ENVIRONMENT_AWS_USE_SSL),
            'exception_mode' => true,
        ]
    ],
];