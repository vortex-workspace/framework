<?php

namespace Stellar\Composer;

if (!defined('DIRECTORY_SEPARATOR')) {
    define('DIRECTORY_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
}

use Composer\Script\Event;
use Stellar\Facades\Collection;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
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
     * @param Event $event
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCreateDirectory
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

        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', "$vendor_path/..");
        }

        if (!defined('FRAMEWORK_PATH')) {
            define('FRAMEWORK_PATH', ROOT_PATH . '/vendor/vortex-workspace/framework');
        }

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
        File::createFromTemplate(
            'providers.php',
            storage_path() . '/internals/cache/packages',
            framework_path('stubs/base_array.php'),
            ['$array' => var_export($providers->toArray(), true)],
            force: true,
            recursive: true
        );
    }
}
