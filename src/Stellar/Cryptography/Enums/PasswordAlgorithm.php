<?php

namespace Stellar\Cryptography\Enums;

use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;

enum PasswordAlgorithm
{
    case Default;
    case BCrypt;
    case Argon2I;
    case Argon2ID;

    public function getCode(): string
    {
        return match ($this->name) {
            self::Argon2I->name => PASSWORD_ARGON2I,
            self::Argon2ID->name => PASSWORD_ARGON2ID,
            default => PASSWORD_BCRYPT
        };
    }

    /**
     * @return array
     * @throws InvalidSettingException
     */
    public function getOptions(): array
    {
        return match ($this->name) {
            self::Argon2I->name => Setting::get(SettingKey::InternalsHashPasswordArgon2IOption->value),
            self::Argon2ID->name => Setting::get(SettingKey::InternalsHashPasswordArgon2IDOption->value),
            default => Setting::get(SettingKey::InternalsHashPasswordBCryptOption->value)
        };
    }
}
