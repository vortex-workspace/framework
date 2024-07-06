<?php

namespace Stellar\Request\Traits;


use Stellar\Helpers\StrTool;

trait Server
{
    public function uri(bool $with_query = false): string
    {
        return $with_query ? $_SERVER['REQUEST_URI'] : StrTool::before($_SERVER['REQUEST_URI'], '?');
    }

    public function method(): ?string
    {
        return $_SERVER['REQUEST_METHOD'] ?? null;
    }
}
