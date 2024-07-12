<?php

namespace Core\Contracts\Boot;

interface ApplicationInterface
{
    public function run();

    public function build(): ApplicationInterface;
}