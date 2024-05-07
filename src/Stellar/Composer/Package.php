<?php

namespace Stellar\Composer;

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../../..');
}

if (!defined('OS_SEPARATOR')) {
    define('OS_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
}

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
use Composer\Script\Event;
use Stellar\Facades\Collection;
use Stellar\Navigation\Directory;
use Stellar\Navigation\File;
use Stellar\Navigation\File\Exceptions\FailedOnDeleteFile;
use Stellar\Navigation\File\Exceptions\FailedOnGetFileContent;
use Stellar\Navigation\File\Exceptions\FileAlreadyExists;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\Stream\Exceptions\FailedToCloseStream;
use Stellar\Navigation\Stream\Exceptions\FailedToOpenStream;
use Stellar\Navigation\Stream\Exceptions\FailedToWriteFromStream;
use Stellar\Navigation\Stream\Exceptions\MissingOpenedStream;
use Stellar\Navigation\Stream\Exceptions\TryCloseNonOpenedStream;

class Package
{
    public const string PACKAGE_PROVIDERS_CACHE_PATH = 'internals/cache/packages/providers.php';

    /**
     * @param PackageEvent $event
     * @return void
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    public static function discover(PackageEvent $event): void
    {
        $vendor_path = $event->getComposer()->getConfig()->get('vendor-dir');
        $autoload_path = "$vendor_path/autoload.php";

        if (!is_file($autoload_path)) {
            return;
        }

        require $autoload_path;

        self::updateAutoloadProviders($event);
    }

    /**
     * @param PackageEvent $event
     * @return void
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    private static function updateAutoloadProviders(PackageEvent $event): void
    {
        if (!($package = $event->getOperation()->getPackage()) instanceof CompletePackage) {
            return;
        }

        $operation_type = $event->getOperation()->getOperationType();
        $extra = $package->getExtra()['vortex'] ?? [];
        $package_name = $package->getName();


        if (empty($package_providers = $extra['providers'])) {
            return;
        }

        $providers = [];

        try {
            $providers = require storage_path(self::PACKAGE_PROVIDERS_CACHE_PATH);
        } catch (PathNotFound) {

        }

        if ($operation_type === OperationType::Uninstall->value && isset($providers[$package_name])) {
            unset($providers[$package_name]);
        } else {
            $providers[$package_name] = $package_providers;
        }

        self::createProvidersFile($providers);
    }

    /**
     * @param array $providers
     * @return void
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    private static function createProvidersFile(array $providers): void
    {
        File::createFromTemplate(
            'providers.php',
            storage_path('internals/cache/packages'),
            root_path('stubs/base_array.php'),
            [
                '$array' => var_export($providers, true)
            ],
            is_real_path: true,
            force: true
        );
    }

    /**
     * @param Event $event
     * @return void
     * @throws FailedOnDeleteFile
     * @throws FailedOnGetFileContent
     * @throws FailedToCloseStream
     * @throws FailedToOpenStream
     * @throws FailedToWriteFromStream
     * @throws FileAlreadyExists
     * @throws MissingOpenedStream
     * @throws PathNotFound
     * @throws TryCloseNonOpenedStream
     */
    public static function autoload(Event $event): void
    {
        $vendor_path = $event->getComposer()->getConfig()->get('vendor-dir');
        $autoload_path = "$vendor_path/autoload.php";

        if (!is_file($autoload_path)) {
            return;
        }

        require $autoload_path;

        $workspaces = Directory::recursiveScan(
            $vendor_path,
            return_full_path: false,
            excludes: ['bin', 'composer', 'autoload.php']
        );

        $providers = [];

        try {
            $providers = require storage_path(self::PACKAGE_PROVIDERS_CACHE_PATH);
        } catch (PathNotFound) {

        }

        $providers = Collection::from($providers);

        foreach ($workspaces as $workspace_path => $workspace) {
            if (!is_array($workspace)) {
                continue;
            }

            foreach ($workspace as $package_path => $package) {
                if (!is_array($package)) {
                    continue;
                }

                if (in_array('composer.json', $package)) {
                    $file = File::get(vendor_path("$workspace_path/$package_path/composer.json"));
                    $composer = json_decode($file, true, flags: JSON_OBJECT_AS_ARRAY);

                    if (isset($composer['extra']['vortex']['providers'])) {
                        $providers->merge([$composer['name'] => $composer['extra']['vortex']['providers']]);
                    }
                }
            }
        }

        self::createProvidersFile($providers->toArray());
    }
}