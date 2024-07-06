<?php

namespace Core\Contracts;

interface RequestInterface
{
    public function method(): ?string;

    public function uri(bool $with_query = false): string;
}