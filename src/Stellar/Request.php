<?php

namespace Stellar;

use Core\Contracts\RequestInterface;
use Stellar\Request\Traits\Getters;
use Stellar\Request\Traits\Server;
use Stellar\Request\Traits\Validate;
use Stellar\Request\Validations\Validation;
use Stellar\Request\Validations\Validator;

class Request implements RequestInterface
{
    use Getters;
    use Server;
    use Validate;

    protected static ?array $attributes = null;
    protected static ?array $query_parameters = null;
    protected static ?array $files = null;
    protected static ?array $cookies = null;

    public function __construct()
    {
        self::$cookies = $_COOKIE ?? [];
        self::$files = $_FILES ?? [];
        self::$query_parameters = $_GET ?? [];
        self::$attributes = array_merge_recursive($_POST ?? [], $_GET ?? []);
        $this->validator = new Validator($this);
        $this->validation = new Validation($this);
    }

    public function __get(string $name)
    {
        return self::get($name);
    }
}
