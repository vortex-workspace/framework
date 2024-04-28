<?php

namespace Stellar\Route\Traits;

trait Getters
{
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    public function getRoute(): ?string
    {
        return $this->route ?? null;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethods->name;
    }

    public function getPrefixedRoute(): string
    {
        return ($this->prefix ?? '') . '/' . $this->getRoute();
    }

    public function getPrefix()
    {

    }
}