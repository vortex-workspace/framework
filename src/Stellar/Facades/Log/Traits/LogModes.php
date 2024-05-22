<?php

namespace Stellar\Facades\Log\Traits;

use Monolog\Level;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

trait LogModes
{
    /**
     * - DEBUG (100): Detailed debug information.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     * @throws InvalidSettingException
     */
    public static function debug(mixed $log): void
    {
        self::getLogger(Level::Debug)->debug($log);
    }

    /**
     * - INFO (200): Interesting events. Examples: User logs in, SQL logs.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function info(mixed $log): void
    {
        self::getLogger(Level::Info)->info($log);
    }

    /**
     * - NOTICE (250): Normal but significant events.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function notice(mixed $log): void
    {
        self::getLogger(Level::Notice)->notice($log);
    }

    /**
     * - WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an
     * API, undesirable things that are not necessarily wrong.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function warning(mixed $log): void
    {
        self::getLogger(Level::Warning)->warning($log);
    }

    /**
     * - ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function error(mixed $log): void
    {
        self::getLogger(Level::Error)->error($log);
    }

    /**
     * - CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function critical(mixed $log): void
    {
        self::getLogger(Level::Critical)->critical($log);
    }

    /**
     * - ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This
     * should trigger the SMS alerts and wake you up.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function alert(mixed $log): void
    {
        self::getLogger(Level::Alert)->alert($log);
    }

    /**
     * - EMERGENCY (600): Emergency: system is unusable.
     * @param mixed $log
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    public static function emergency(mixed $log): void
    {
        self::getLogger(Level::Emergency)->emergency($log);
    }
}