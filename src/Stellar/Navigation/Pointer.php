<?php

namespace Stellar\Navigation;

use Stellar\Navigation\Pointer\Enums\OpenMode;
use Stellar\Navigation\Pointer\Exceptions\FailedToClosePointer;
use Stellar\Navigation\Pointer\Exceptions\FailedToOpenFilePointer;
use Stellar\Navigation\Pointer\Exceptions\TryCloseNonOpenedPointer;

class Pointer
{
    private $pointer;

    public static function make(string $file_path, OpenMode $mode, bool $use_include_path = false): static
    {
        return (new static($file_path, $mode, $use_include_path));
    }

    /**
     * @return false|resource
     * @throws FailedToOpenFilePointer
     */
    public function tryOpen()
    {
        if (($pointer = fopen($this->file_path, $this->mode->value, $this->use_include_path)) === false) {
            throw new FailedToOpenFilePointer($this->file_path);
        }

        $this->pointer = $pointer;

        return $this->pointer;
    }

    private function __construct(
        private readonly string   $file_path,
        private readonly OpenMode $mode,
        private readonly bool     $use_include_path = false,
    )
    {
    }

    public function open()
    {
        return $this->pointer = fopen($this->file_path, $this->mode->value, $this->use_include_path);
    }

    public function close(): bool
    {
        if (isset($this->pointer) && $this->pointer !== false) {
            if (fclose($this->pointer) === true) {
                $this->pointer = null;

                return  true;
            }

            return false;
        }

        return false;
    }

    /**
     * @return bool
     * @throws FailedToClosePointer
     * @throws TryCloseNonOpenedPointer
     */
    public function tryClose(): bool
    {
        if (isset($this->pointer) && $this->pointer !== false) {
            if (fclose($this->pointer) === true) {
                $this->pointer = null;

                return  true;
            }

            throw new FailedToClosePointer($this->file_path);
        }

        throw new TryCloseNonOpenedPointer($this->file_path);
    }
}