<?php

use Stellar\Vortex\Facades\Log\Enum\LogFileFormat;
use Stellar\Vortex\Facades\Log\Enum\LogHandler;

return [
    /**
     * Specify the log handlers to be used on Vortex application.
     * 'handler' => Stellar\Vortex\Facades\Log\Enum\LogHandler
     * https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#handlers
     */
    'handler' => LogHandler::STREAM_HANDLER,

    /**
     * Set the format to create new files
     * 'use_format' => Stellar\Vortex\Facades\Log\Enum\LogFileFormat
     */
    'use_format' => LogFileFormat::SINGLE,

    /**
     * Set the individual log file settings
     */
    'formats' => [
        'single' => [
            'filename' => '.log'
        ],
        'timestamp' => 'daily'
    ],
];