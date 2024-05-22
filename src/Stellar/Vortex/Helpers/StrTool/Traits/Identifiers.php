<?php

namespace Stellar\Vortex\Helpers\StrTool\Traits;

use Ramsey\Uuid\Uuid;
use Stellar\Vortex\Helpers\StrTool\Enum\UuidVersion;
use Ulid\Ulid;

trait Identifiers
{
    public static function ulid(): string
    {
        return (string)Ulid::generate();
    }

    public static function ulidFromString(string $string): string
    {
        return (string)Ulid::fromString($string);
    }

    public static function ulidFromTimestamp(int $milliseconds): int
    {
        return Ulid::fromTimestamp($milliseconds)->toTimestamp();
    }

    public static function uuid(UuidVersion $version)
    {
        return Uuid::{$version->value}();
    }
}