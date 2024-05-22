<?php

namespace Stellar\Navigation;

use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream\Enums\OpenMode;
use Stellar\Navigation\Stream\Exceptions\FailedToCloseStream;
use Stellar\Navigation\Stream\Exceptions\FailedToOpenStream;
use Stellar\Navigation\Stream\Exceptions\FailedToWriteFromStream;
use Stellar\Navigation\Stream\Exceptions\MissingOpenedStream;
use Stellar\Navigation\Stream\Exceptions\TryCloseNonOpenedStream;

class Stream
{
    private $resource;

    /**
     * @param string $file_path
     * @param OpenMode $mode
     * @param bool $use_include_path
     * @return static
     */
    public static function make(
        string   $file_path,
        OpenMode $mode,
        bool     $use_include_path = false
    ): static
    {
        return (new static($file_path, $mode, $use_include_path));
    }

    /**
     * @return $this
     * @throws FailedToOpenStream
     */
    public function open(): static
    {
        if (($pointer = fopen($this->file_path, $this->mode->value, $this->use_include_path)) === false) {
            throw new FailedToOpenStream($this->file_path);
        }

        $this->resource = $pointer;

        return $this;
    }

    private function __construct(
        private readonly string   $file_path,
        private readonly OpenMode $mode,
        private readonly bool     $use_include_path = false,
    )
    {

    }

    /**
     * @param string $data
     * @param int|null $length
     * @return $this
     * @throws FailedToWriteFromStream
     * @throws MissingOpenedStream
     */
    public function write(string $data, ?int $length = null): static
    {
        if (!isset($this->resource) || $this->resource === false) {
            throw new MissingOpenedStream($this->file_path);
        }

        if (fwrite($this->resource, $data, $length) === false) {
            throw new FailedToWriteFromStream($this->file_path);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws FailedToCloseStream
     * @throws TryCloseNonOpenedStream
     */
    public function close(): static
    {
        if (isset($this->resource) && $this->resource !== false) {
            if (fclose($this->resource) === true) {
                $this->resource = null;

                return $this;
            }

            throw new FailedToCloseStream($this->file_path);
        }

        throw new TryCloseNonOpenedStream($this->file_path);
    }

    public function getResource()
    {
        return $this->resource;
    }
}