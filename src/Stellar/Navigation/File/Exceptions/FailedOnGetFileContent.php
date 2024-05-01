<?php

namespace Stellar\Navigation\File\Exceptions;

use Monolog\Level;
use Stellar\Throwable\Exceptions\Contracts\Exception;
use Stellar\Throwable\Exceptions\Enum\ExceptionCode;

class FailedOnGetFileContent extends Exception
{
    public function __construct(
        public readonly string $file_path,
        public readonly int $offset,
        public readonly ?int $length,
    )
    {
        parent::__construct(
            "Fail on try get file content, file_path:\"$this->file_path\", offset: $this->offset , length: $this->length.",
            ExceptionCode::CATCH_EXCEPTION,
            Level::Warning
        );
    }
}