<?php

namespace Stellar\Storage;

use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;

class StorageDrive
{
    public const string PUBLIC_PARTITION = 'public';
    public const string PRIVATE_PARTITION = 'private';

    private string $drive;
    private string $partition;

    public function __construct(?string $drive = null, ?string $partition = null)
    {
        if ($drive === null) {
            $drive = Setting::get(SettingKey::StorageDrivesDefault->value, 'local');
        }

        if ($partition === null) {
            $partition = self::PUBLIC_PARTITION;
        }

        $this->drive = $drive;
        $this->partition = $partition;
    }

    public function public(): static
    {
        $this->partition = self::PUBLIC_PARTITION;

        return $this;
    }

    public function private(): static
    {
        $this->partition = self::PRIVATE_PARTITION;

        return $this;
    }

    public function get(string $path)
    {
        dd($this->drive . '/' . $this->partition . '/' . $path);
    }
}