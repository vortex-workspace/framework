<?php

namespace Core\Helpers;

class Environment
{
    public const APP_NAME = 'APP_NAME';
    public const APP_ENV = 'APP_ENV';
    public const APP_URL = 'APP_URL';
    public const DB_CONNECTION = 'DB_CONNECTION';
    public const DB_HOST = 'DB_HOST';
    public const DB_PORT = 'DB_PORT';
    public const DB_DATABASE = 'DB_DATABASE';
    public const DB_USERNAME = 'DB_USERNAME';
    public const DB_PASSWORD = 'DB_PASSWORD';
    public const DB_CHARSET = 'DB_CHARSET';
    public const APP_LOCALHOST_SERVER_PORT = 'APP_LOCALHOST_SERVER_PORT';
    public const TIME_ZONE = 'TIME_ZONE';
    public const DATE_FORMAT = 'DATE_FORMAT';
    public const TIME_FORMAT = 'TIME_FORMAT';
    public const MAIL_HOST = 'MAIL_HOST';
    public const MAIL_USERNAME = 'MAIL_USERNAME';
    public const MAIL_PASSWORD = 'MAIL_PASSWORD';
    public const MAIL_SMTP_SECURE = 'MAIL_SMTP_SECURE';
    public const MAIL_PORT = 'MAIL_PORT';

    public static function appName()
    {
        return $_ENV[self::APP_NAME] ?? null;
    }

    public static function appEnv()
    {
        return $_ENV[self::APP_ENV] ?? null;
    }

    public static function appUrl()
    {
        return $_ENV[self::APP_URL] ?? null;
    }

    public static function appLocalhostServerPort()
    {
        return $_ENV[self::APP_LOCALHOST_SERVER_PORT] ?? null;
    }

    public static function dbConnection()
    {
        return $_ENV[self::DB_CONNECTION] ?? null;
    }

    public static function dbHost()
    {
        return $_ENV[self::DB_HOST] ?? null;
    }

    public static function dbPort()
    {
        return $_ENV[self::DB_PORT] ?? null;
    }

    public static function dbDatabase()
    {
        if ($_ENV[self::DB_DATABASE] === '') {
            return null;
        }
        return ($db_name = $_ENV[self::DB_DATABASE]) === '' || $db_name === null ? null : $db_name;
    }

    public static function dbUsername()
    {
        return $_ENV[self::DB_USERNAME] ?? null;
    }

    public static function dbPassword()
    {
        return $_ENV[self::DB_PASSWORD] ?? null;
    }

    public static function dbCharset()
    {
        return $_ENV[self::DB_CHARSET] ?? null;
    }

    public static function timeZone()
    {
        return $_ENV[self::TIME_ZONE] ?? null;
    }

    public static function dateFormat()
    {
        return $_ENV[self::DATE_FORMAT] ?? null;
    }

    public static function timeFormat()
    {
        return $_ENV[self::TIME_FORMAT] ?? null;
    }

    public static function dateTimeFormat(): ?string
    {
        if (key_exists('DATE_FORMAT', $_ENV) && key_exists('TIME_FORMAT', $_ENV)) {
            return $_ENV['DATE_FORMAT'] . ' ' . $_ENV['TIME_FORMAT'];
        }

        return null;
    }

    public static function mailHost()
    {
        return $_ENV[self::MAIL_HOST] ?? null;
    }

    public static function mailUsername()
    {
        return $_ENV[self::MAIL_USERNAME] ?? null;
    }

    public static function mailPassword()
    {
        return $_ENV[self::MAIL_PASSWORD] ?? null;
    }

    public static function mailSmtpSecure()
    {
        return $_ENV[self::MAIL_SMTP_SECURE] ?? null;
    }

    public static function mailPort()
    {
        return $_ENV[self::MAIL_PORT] ?? null;
    }

    public static function session_hours_duration()
    {
        return $_ENV['SESSION_HOURS_DURATION'] ?? null;
    }
}
