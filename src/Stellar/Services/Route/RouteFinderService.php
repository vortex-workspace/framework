<?php

namespace Stellar\Services\Route;

use Stellar\Navigation\Directory;
use Stellar\Navigation\Enums\ApplicationPath;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Router;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

class RouteFinderService extends AbstractRouteFinderService
{
    /**
     * @return void
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     */
    public function findRoutes(): void
    {
        $route_files = [];

        try {
            $route_files = Directory::scan(root_path(ApplicationPath::Routes->value));
        } catch (PathNotFound) {
            return;
        }

        foreach ($route_files as $route_file) {
            try {
                require_once root_path(ApplicationPath::Routes->additionalPath($route_file));
            } catch (PathNotFound) {
            }
        }

        Router::getInstance()->updateRoutesWithPrefix()->loadNames();
    }
}