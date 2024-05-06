<?php

namespace Stellar\Composer;

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../../..');
}

if (!defined('OS_SEPARATOR')) {
    define('OS_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
}

require_once ROOT_PATH . '/vendor/autoload.php';

use Composer\Installer\PackageEvent;
use Composer\Package\CompletePackage;
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
        /** @var CompletePackage $package */
        if (($package = $event->getOperation()->getPackage()) instanceof CompletePackage) {
            self::updateAutoloadProviders(
                $package->getExtra()['vortex'] ?? [],
                $package->getName(),
                $event->getOperation()->getOperationType()
            );
        }
    }

    /**
     * @param array $extra
     * @param string $package_name
     * @param string $operation_type
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
    private static function updateAutoloadProviders(array $extra, string $package_name, string $operation_type): void
    {
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
}