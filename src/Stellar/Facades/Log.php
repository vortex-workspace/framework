<?php

namespace Stellar\Facades;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Stellar\Facades\Log\Enum\LogFileFormat;
use Stellar\Facades\Log\Enum\LogHandler;
use Stellar\Facades\Log\Traits\LogModes;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Directory\Exceptions\FailedOnScanDirectoryException;
use Stellar\Navigation\Enums\ProjectPath;
use Stellar\Navigation\Helpers\Path;
use Stellar\Navigation\Path\Exceptions\FailedOnDeleteException;
use Stellar\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Navigation\Path\Exceptions\TypeNotMatchException;
use Stellar\Setting;

class Log
{
    use LogModes;

    private static Logger $logger;
    private static Level $level;

    private function __construct()
    {
    }

    /**
     * @param Level $level
     * @return Logger
     * @throws FailedOnDeleteException
     * @throws FailedOnScanDirectoryException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    private static function getLogger(Level $level): Logger
    {
        if (!isset(self::$logger) || $level !== self::$level) {
            self::setLogger($level);
        }

        return self::$logger;
    }

    /**
     * @param Level $level
     * @return void
     * @throws FailedOnDeleteException
     * @throws FailedOnScanDirectoryException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    private static function setLogger(Level $level): void
    {
        self::$level = $level;

        self::$logger = new Logger('Vortex');
        self::$logger->pushHandler(self::resolveHandler($level));
    }

    /**
     * @param Level $level
     * @return AbstractProcessingHandler
     * @throws FailedOnScanDirectoryException
     * @throws FailedOnDeleteException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    private static function resolveHandler(Level $level): AbstractProcessingHandler
    {
        try {
            $log_directory_path = Path::fullPath(ProjectPath::LOGS->value);
        } catch (PathNotFoundException $exception) {
            Directory::create($exception->getPath(), './');

            $log_directory_path = Path::fullPath(ProjectPath::LOGS->value);
        }

        $handler = Setting::get('logs.handler', LogHandler::STREAM_HANDLER);

        return new $handler->value(self::resolveFileName($log_directory_path), $level);
    }

    private static function resolveFileName(string $log_directory_path): string
    {
        $log_format = Setting::get('logs.use_format', LogFileFormat::SINGLE);
        $log_single_filename = Setting::get('logs.formats.single.filename', '.log');

        if ($log_format === LogFileFormat::SINGLE) {
            return $log_directory_path . '/' . $log_single_filename;
        }

        return $log_directory_path . '/' . $log_single_filename;
    }
}