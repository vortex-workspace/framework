<?php

namespace Stellar\Facades;

use Stellar\Facades\Cryptography\DecryptBuilder;
use Stellar\Facades\Cryptography\EncryptBuilder;
use Stellar\Facades\Cryptography\Enum\CipherMethods;

class Crypto
{
    public static function encrypt(string $data, CipherMethods $cypher_method, string $salt_key): EncryptBuilder
    {
        return new EncryptBuilder($data, $cypher_method, $salt_key);
    }

    public static function decrypt($data, $cypher_method, $salt_key): DecryptBuilder
    {
        return new DecryptBuilder($data, $cypher_method, $salt_key);
    }
}