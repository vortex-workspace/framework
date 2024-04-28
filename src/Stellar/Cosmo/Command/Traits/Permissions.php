<?php

namespace Stellar\Cosmo\Command\Traits;

use Stellar\Boot\Application;

trait Permissions
{
    /**
     * @param Application $application
     * @return bool
     */
    public static function can(Application $application): bool
    {
        return true;
    }

    /**
     * @param Application $application
     * @return bool
     */
    public static function canRun(Application $application): bool
    {
        return true;
    }

    /**
     * @param Application $application
     * @return bool
     */
    public static function canSee(Application $application): bool
    {
        return true;
    }
}