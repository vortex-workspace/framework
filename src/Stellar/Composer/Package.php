<?php

namespace Stellar\Composer;

define('ROOT_PATH', __DIR__ . '/../../../');

require_once ROOT_PATH . 'vendor/autoload.php';

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Stellar\Navigation\File;
use Stellar\Navigation\Path\Exceptions\PathNotFoundException;

class Package
{
    public static function discover(PackageEvent $event)
    {
        /** @var CompletePackage $package */
        if (($package = $event->getOperation()->getPackage()) instanceof CompletePackage) {
            $extra = $package->getExtra();

            if (!empty($vortex_extra = $extra['vortex'])) {
                self::extractAutoloadProviders($vortex_extra);
            }
        }
    }

    private static function extractAutoloadProviders(array $extra)
    {
        if (!empty($providers = $extra['providers'])) {
            try {
                $providers = File::getContent(ROOT_PATH . 'Storage/vortex/package/providers.php');
            } catch (PathNotFoundException $exception) {
                File::create('providers.php', ROOT_PATH . 'Storage/vortex/package/', true);
            }
        }
    }
}