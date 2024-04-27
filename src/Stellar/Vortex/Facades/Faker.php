<?php

namespace Stellar\Vortex\Facades;

use BadMethodCallException;
use Faker\Factory;
use Faker\Generator;
use Stellar\Core\Throwables\Exceptions\Generics\BadPropertyCallException;
use Stellar\Vortex\Facades\Faker\Enum\Locale;

/** @mixin Generator */
class Faker
{
    private Generator $generator;

    /**
     * @param Locale $locale
     * @throws BadMethodCallException
     */
    public function __construct(Locale $locale = Locale::ENGLISH_US)
    {
        $this->generator = Factory::create($locale->value);
    }

    public function __call(string $name, array $arguments): mixed
    {
       return $this->generator->$name(...$arguments);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
       return $this->generator->$name;
    }
}