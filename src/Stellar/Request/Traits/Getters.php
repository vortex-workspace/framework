<?php

namespace Stellar\Request\Traits;

trait Getters
{
    private const string ALL_METHOD = 'all';
    private const string COOKIE_METHOD = 'cookies';
    private const string ATTRIBUTE_METHOD = 'attributes';
    private const string QUERY_PARAMETER_METHOD = 'query_parameters';
    private const string FILE_METHOD = 'files';

    /**
     * @param string|array|null $keys
     * @return mixed
     */
    public static function get(string|array|null $keys = null): mixed
    {
        return self::getStaticAttributes($keys, self::ALL_METHOD);
    }

    /**
     * @param array|string|null $attributes
     * @return mixed
     */
    public static function getAttributes(array|string|null $attributes = null): mixed
    {
        return self::getStaticAttributes($attributes, self::ATTRIBUTE_METHOD);
    }

    /**
     * @param array|string|null $parameters
     * @return mixed
     */
    public static function getQueryParameters(array|string|null $parameters = null): mixed
    {
        return self::getStaticAttributes($parameters, self::QUERY_PARAMETER_METHOD);
    }

    /**
     * @param array|string|null $cookies
     * @return mixed
     */
    public static function getCookies(array|string|null $cookies = null): mixed
    {
        return self::getStaticAttributes($cookies, self::COOKIE_METHOD);
    }

    /**
     * @param array|string|null $files
     * @return mixed
     */
    public static function getFiles(array|string|null $files = null): mixed
    {
        return self::getStaticAttributes($files, self::FILE_METHOD);
    }

    /**
     * @param array|string|null $parameters
     * @param string $attribute
     * @return mixed
     */
    private static function getStaticAttributes(array|string|null $parameters, string $attribute): mixed
    {
        if (self::$$attribute === null) {
            new self();
        }

        $attributes_copy = self::unsetProperties(self::$$attribute);

        if ($parameters === null) {
            return $attributes_copy;
        }

        if (is_array($parameters)) {
            $return = [];

            foreach ($parameters as $parameter) {
                $return[$parameter] = $attributes_copy[$parameter] ?? null;
            }

            return $return;
        }

        return $attributes_copy[$parameters] ?? null;
    }

    private static function unsetProperties(array $array): array
    {
        foreach (['csrf_token', 'vortex_method', 'LAST_ROUTE', 'vortex_redirect', 'ROUTE_LIST_CALL'] as $unset_key) {
            unset($array[$unset_key]);
        }

        return $array;
    }

    public function getByKey(array|string|null $attributes = null): mixed
    {
        return self::get($attributes);
    }

    public function attributes(array|string|null $attributes = null): mixed
    {
        return self::getAttributes($attributes);
    }

    public function queryParameters(array|string|null $parameters = null): mixed
    {
        return self::getQueryParameters($parameters);
    }

    public function files(array|string|null $files = null): mixed
    {
        return self::getFiles($files);
    }

    public function cookies(array|string|null $cookies = null): mixed
    {
        return self::getCookies($cookies);
    }
}
