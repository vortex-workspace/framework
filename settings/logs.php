<?php

use Stellar\Facades\Log\Enum\LogFileFormat;
use Stellar\Facades\Log\Enum\LogHandler;

return [
    /**
     * Set the format to create new files
     * 'format' => Stellar\Vortex\Facades\Log\Enum\LogFileFormat
     * Default: LogFileFormat::SINGLE
     */
    'format' => LogFileFormat::DATE,

    /**
     * Whether the messages that are handled can bubble up the stack or not
     */
    'bubble' => false,

    /**
     * Set the max number of files, to disable set null.
     * Default: null
     */
    'max_files' => null,

    /**
     * Set the individual log file settings
     */
    'formats' => [
        'single' => [
            'filename' => 'vortex.log'
        ],
        'date' => [
            'format' => 'Y-m-d',
            'suffix' => 'log',
        ],
    ],

    /**
     * Set the line format prefix.
     */
    'line_formatter' => [
        'date_format' => 'Y-m-d H:m:s'
    ]
];