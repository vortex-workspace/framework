<?php

namespace Stellar\Vortex;

use Stellar\Core\Contracts\RequestInterface;
use Stellar\Vortex\Request\Traits\Getters;
use Stellar\Vortex\Request\Traits\Server;
use Stellar\Vortex\Request\Traits\Setters;
use Stellar\Vortex\Request\Traits\Validate;
use Stellar\Vortex\Request\Validations\Validation;
use Stellar\Vortex\Request\Validations\Validator;

class Request implements RequestInterface
{
    use Getters;
    use Server;
    use Setters;
    use Validate;

    protected static ?array $attributes = null;
    protected static ?array $query_parameters = null;
    protected static ?array $all = null;
    protected static ?array $files = null;
    protected static ?array $cookies = null;

    public function __construct()
    {
        self::setCookies();
        self::setFiles();
        self::setQueryParameters();
        self::setAttributes();
        self::setAll();

        $this->validator = new Validator($this);
        $this->validation = new Validation($this);
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        return self::$name(...$arguments);
    }

    public function __get(string $name)
    {
        return self::get($name);
    }
}
