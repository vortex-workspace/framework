<?php

namespace Stellar\Facades;

use Carbon\Carbon;
use Monolog\Level;
use Monolog\Logger;
use Stellar\Facades\Log\Enum\LogFileFormat;
use Stellar\Facades\Log\Traits\HandlerResolvers;
use Stellar\Facades\Log\Traits\LogModes;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Setting;
use Stellar\Settings\Exceptions\InvalidSettingException;

class Log
{
    use HandlerResolvers, LogModes;

    private const bool DEFAULT_BUBBLE = false;
    private const int DEFAULT_MAX_ROTATING_FILES = 10;

    public static string $current_log_file;
    private static Logger $logger;
    private static Level $level;

    private function __construct()
    {
    }

    /**
     * @param Level $level
     * @return Logger
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
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
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    private static function setLogger(Level $level): void
    {
        self::$level = $level;
        self::$logger = new Logger('Vortex');
        self::$logger->pushHandler(self::resolveHandler($level));
    }

    /**
     * @param string $log_directory_path
     * @param array $log_settings
     * @return string
     * @throws InvalidSettingException
     */
    private static function resolveLogFilepath(string $log_directory_path, array $log_settings): string
    {
        if (isset(self::$current_log_file)) {
            return self::$current_log_file;
        }

        $log_directory_path = StrTool::forceFinishWith($log_directory_path, '/');

        if (!isset($log_settings['format'])) {
            $log_settings['format'] = LogFileFormat::SINGLE;
        }

        if ($log_settings['format'] === LogFileFormat::DATE) {
            $date_file = Carbon::now()->format($log_settings['formats']['date']['format'] ?? 'Y-m-d');
            self::$current_log_file = "$log_directory_path$date_file" . StrTool::forceStartWith($log_settings['formats']['date']['suffix'], '.');
            return self::$current_log_file;
        }

        $log_single_filename = Setting::get('logs.formats.single.filename', '.log');
        $log_single_filename = StrTool::removeIfStartAndFinishWith($log_single_filename, '/');
        self::$current_log_file = "$log_directory_path/$log_single_filename";
        return self::$current_log_file;
    }

    /**
     * @param string $log_directory_path
     * @param array $log_settings
     * @return string
     * @throws InvalidSettingException
     */
    public static function getFilename(string $log_directory_path, array $log_settings): string
    {
        return self::resolveLogFilepath($log_directory_path, $log_settings);
    }
}