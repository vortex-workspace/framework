<?php

namespace Stellar\Vortex\Facades;

use Stellar\Vortex\Facades\Cryptography\DecryptBuilder;
use Stellar\Vortex\Facades\Cryptography\EncryptBuilder;
use Stellar\Vortex\Facades\Cryptography\Enum\CipherMethods;

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