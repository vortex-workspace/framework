<?php

namespace Stellar\Cryptography;

use Stellar\Cryptography\Enums\PasswordAlgorithm;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;

class Hash
{
    /**
     * @param string $string
     * @return string
     * @throws InvalidSettingException
     */
    public static function password(string $string): string
    {
        $algorithm = Setting::get(SettingKey::InternalsHashPasswordAlgorithm->value, PasswordAlgorithm::BCrypt);

        return password_hash(
            $string,
            $algorithm->getCode(),
            $algorithm->getOptions()
        );
    }

    public static function isEqual(string $string, string $hashed_string): bool
    {
        return password_verify($hashed_string, $string);
    }

    /**
     * @param string $hashed_password
     * @param PasswordAlgorithm|null $algorithm
     * @param array|null $options
     * @return bool
     * @throws InvalidSettingException
     */
    public static function needRehash(
        string             $hashed_password,
        ?PasswordAlgorithm $algorithm = null,
        ?array             $options = null
    ): bool
    {
        $settingAlgorithm = Setting::get(
            SettingKey::InternalsHashPasswordAlgorithm->value,
            PasswordAlgorithm::BCrypt
        );

        return password_needs_rehash(
            $hashed_password,
            ($algorithm ?? $settingAlgorithm)->getCode(),
            $options ?? $settingAlgorithm->getOptions()
        );
    }

    public static function hashFile()
    {
        return hash_file();
    }
}