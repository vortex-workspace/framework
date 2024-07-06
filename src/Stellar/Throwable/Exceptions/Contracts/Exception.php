<?php

namespace Stellar\Throwable\Exceptions\Contracts;

use Exception as PhpException;
use Monolog\Level;
use Stellar\Facades\Log;
use Stellar\Setting;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;
use Throwable;

abstract class Exception extends PhpException
{
    public function __construct(
        string        $message,
        ExceptionCode $code,
        Level         $level = Level::Debug,
        ?Throwable    $previous = null
    )
    {
        parent::__construct($message, $code->value, $previous);

        $exception_settings = Setting::get('exceptions', []);

        if ($this->logIsEnable($exception_settings) && $this->levelLogIsEnable($exception_settings, $level)) {
            Log::{strtolower($level->name)}(json_encode([
                'message' => $message,
                'trace' => $this->getTraceAsString(),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'code' => $this->getCode(),
                'previous' => $this->getPrevious(),
            ]));
        }
    }

    private function logIsEnable(array $exception_settings): bool
    {
        return $exception_settings['log'] ?? true;
    }

    private function levelLogIsEnable(array $exception_settings, Level $level): bool
    {
        if (!isset($exception_settings['level_map'])) {
            return true;
        }

        return $exception_settings['level_map'][$level->name] ?? true;
    }
}