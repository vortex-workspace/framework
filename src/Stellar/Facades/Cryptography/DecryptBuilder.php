<?php

namespace Stellar\Facades\Cryptography;

use Stellar\Facades\Cryptography\Contracts\BaseCryptBuilder;

class DecryptBuilder extends BaseCryptBuilder
{
    public function make(): false|string
    {
        return openssl_decrypt(
            $this->data,
            $this->cipher_method->value,
            $this->salt_key,
            $this->option,
            $this->initialization_vector,
            $this->tag,
            $this->additional_authentication_data
        );
    }
}