<?php

namespace Stellar\Cryptography;

use Stellar\Cryptography\Enums\Algorithm;
use Stellar\Cryptography\Enums\HmacAlgorithm;
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

    /**
     * @param Algorithm $algorithm
     * @param string $file_path
     * @param bool $return_binary
     * @param array $algorithm_options
     * @return false|string
     */
    public static function hashFile(
        Algorithm $algorithm,
        string    $file_path,
        bool      $return_binary = false,
        array     $algorithm_options = []
    ): false|string
    {
        return hash_file($algorithm->value, $file_path, $return_binary, $algorithm_options);
    }

    /**
     * @param Algorithm $algorithm
     * @param string $data
     * @param bool $return_binary
     * @return string
     */
    public static function make(Algorithm $algorithm, string $data, bool $return_binary = false): string
    {
        return hash($algorithm->name, $data, $return_binary);
    }

    /**
     * @param HmacAlgorithm $algorithm
     * @param string $data
     * @param string $secret_key
     * @param bool $return_binary
     * @return string
     */
    public static function hmac(
        HmacAlgorithm $algorithm,
        string        $data,
        string        $secret_key,
        bool          $return_binary = false
    ): string
    {
        return hash_hmac($algorithm->name, $data, $secret_key, $return_binary);
    }

    /**
     * @param HmacAlgorithm $algorithm
     * @param string $file_path
     * @param string $secret_key
     * @param bool $return_binary
     * @return false|string
     */
    public static function hmacHashFile(
        HmacAlgorithm $algorithm,
        string        $file_path,
        string        $secret_key,
        bool          $return_binary = false,
    ): false|string
    {
        return hash_hmac_file($algorithm->value, $file_path, $secret_key, $return_binary);
    }
}