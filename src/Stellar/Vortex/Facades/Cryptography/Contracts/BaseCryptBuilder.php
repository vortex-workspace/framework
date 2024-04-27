<?php

namespace Stellar\Vortex\Facades\Cryptography\Contracts;

use Stellar\Vortex\Facades\Cryptography\Enum\CipherMethods;

abstract class BaseCryptBuilder
{
    protected string $data;
    protected CipherMethods $cipher_method;
    protected string $salt_key;
    protected int $option = 0;
    protected string $initialization_vector = '';
    protected ?string $tag = null;
    protected string $additional_authentication_data = '';
    protected int $tag_length = 16;

    public function __construct(string $data, CipherMethods $cipher_method, string $salt_key)
    {
        $this->data = $data;
        $this->cipher_method = $cipher_method;
        $this->salt_key = $salt_key;
    }

    /**
     * @param int $option | Use OPENSSL_RAW_DATA or OPENSSL_ZERO_PADDING
     * @return BaseCryptBuilder
     */
    public function option(int $option): static
    {
        $this->option = $option;

        return $this;
    }

    public function initializationVector(string $iv): static
    {
        $this->initialization_vector = $iv;

        return $this;
    }

    /**
     * @param string $tag | Authentication tag when using AEAD cipher mode (GCM or CCM)
     * @return BaseCryptBuilder
     */
    public function tag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function additionalAuthenticatedData(string $aad): static
    {
        $this->additional_authentication_data = $aad;

        return $this;
    }

    public function tagLength(int $length): static
    {
        $this->tag_length = $length;

        return $this;
    }

    abstract public function make(): false|string;
}