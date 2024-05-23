<?php

namespace Stellar\Request\Traits;

trait Getters
{
    /**
     * @param string|array|null $keys
     * @return mixed
     */
    public static function get(string|array $keys = null): mixed
    {
        if (!is_array($keys)) {
            return self::$attributes[$keys];
        }

        if (empty($keys)) {
            return null;
        }

        $values = [];

        foreach ($keys as $key) {
            $values[$key] = self::$attributes[$key] ?? null;
        }

        return $values;
    }

    /**
     * @param array|string|null $attributes
     * @return mixed
     */
    public static function getAttributes(array|string|null $attributes = null): mixed
    {
        return self::getStaticAttributes($attributes, self::$attributes);
    }

    /**
     * @param array|string|null $parameters
     * @return mixed
     */
    public static function getQueryParameters(array|string|null $parameters = null): mixed
    {
        return self::getStaticAttributes($parameters, self::$query_parameters);
    }

    /**
     * @param array|string|null $cookies
     * @return mixed
     */
    public static function getCookies(array|string|null $cookies = null): mixed
    {
        return self::getStaticAttributes($cookies, self::$cookies);
    }

    /**
     * @param array|string|null $files
     * @return mixed
     */
    public static function getFiles(array|string|null $files = null): mixed
    {
        return self::getStaticAttributes($files, self::$files);
    }

    /**
     * @param array|string|null $parameters
     * @param array|null $attribute
     * @return mixed
     */
    private static function getStaticAttributes(array|string|null $parameters, ?array $attribute): mixed
    {
        if ($attribute === null) {
            return null;
        }

        $attributes_copy = self::unsetProperties($attribute);

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
