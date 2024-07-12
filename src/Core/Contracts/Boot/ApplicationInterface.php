<?php

namespace Core\Contracts\Boot;

interface ApplicationInterface
{
    public function run(): ApplicationInterface;

    public function build(): ApplicationInterface;
}