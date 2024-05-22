<?php

namespace Stellar\Facades\Log\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Setting;
use Stellar\Settings\Exceptions\InvalidSettingException;

trait HandlerResolvers
{
    /**
     * @param Level $level
     * @return HandlerInterface
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     * @throws InvalidSettingException
     */
    private static function resolveHandler(Level $level): HandlerInterface
    {
        try {
            $log_directory_path = storage_path('logs');
        } catch (PathNotFound $exception) {
            Directory::create($exception->path, './');
            $log_directory_path = storage_path('logs');
        }

        $log_settings = Setting::get('logs');
        $bubble = $log_settings['bubble'] ?? self::DEFAULT_BUBBLE;
        $log_path = self::resolveLogFilepath($log_directory_path, $log_settings ?? []);

        return self::discoverHandler($log_path, $level, $bubble, $log_settings)
            ->setFormatter(new LineFormatter(
                null,
                $log_settings['line_formatter']['date_format'] ?? 'Y-m-d H:m:s',
                false,
                true,
            ));
    }

    private static function discoverHandler(
        string $log_path,
        Level  $level,
        bool   $bubble,
        array  $log_settings
    ): RotatingFileHandler|StreamHandler
    {
        if (!empty($log_settings['max_files']) && $log_settings['max_files'] > 0) {
            return new RotatingFileHandler($log_path, $log_settings['max_files'], $level, $bubble);
        }

        return new StreamHandler($log_path, $level, $bubble);
    }
}