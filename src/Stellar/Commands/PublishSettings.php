<?php

namespace Stellar\Commands;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use Cosmo\Option;
use Cosmo\Option\Enums\OptionMode;
use Stellar\Boot\Application;
use Stellar\Navigation\Directory\Exceptions\DirectoryAlreadyExist;
use Stellar\Navigation\Directory\Exceptions\FailedOnCreateDirectory;
use Stellar\Navigation\File;
use Stellar\Navigation\File\Exceptions\FailedOnCopyFile;
use Stellar\Navigation\File\Exceptions\TryCopyFileButAlreadyExists;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

class PublishSettings extends Command
{
    protected function name(): string
    {
        return 'publish:settings';
    }

    /**
     * @return CommandResponse
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     */
    protected function handle(): CommandResponse
    {
        $setting_files = Application::getInstance()->getSettingFiles();

        if ($this->input->getOption('provider')) {
            return $this->publishProviderSettings($setting_files);
        }

        $formatted_files = [];

        foreach ($setting_files as $files) {
            foreach ($files as $file) {
                $file = File::realPath($file);
                $formatted_files[$file] = $file;
            }
        }

        if ($this->input->getOption('all')) {
            foreach ($formatted_files as $setting) {
                $this->publishSetting($setting);
            }

            $this->breakLine();

            return CommandResponse::SUCCESS;
        }

        $settings = $this->multiselect('Setting File', $formatted_files);

        if (empty($settings)) {
            $this->warningBlock('No setting files selected.');

            return CommandResponse::FAILED;
        }

        foreach ($settings as $setting) {
            $this->publishSetting($setting);
        }

        $this->breakLine();

        return CommandResponse::SUCCESS;
    }

    /**
     * @param array $setting_files
     * @return CommandResponse
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     */
    private function publishProviderSettings(array $setting_files): CommandResponse
    {
        $providers = [];

        foreach ($setting_files as $provider => $setting) {
            $providers[] = $provider;
        }

        if (empty($providers)) {
            $this->warningBlock('No providers settings found.');

            return CommandResponse::SUCCESS;
        }

        $providers = $this->multiselect('Providers', $providers);

        if (empty($providers)) {
            $this->warningBlock('No providers selected.');

            return CommandResponse::FAILED;
        }

        $this->indexRow('Setting file', 'Status');

        foreach ($providers as $provider) {
            foreach ($setting_files[$provider] as $setting_file) {
                $this->publishSetting(File::realPath($setting_file));
            }
        }

        $this->breakLine();

        return CommandResponse::SUCCESS;
    }

    /**
     * @param string $path
     * @return void
     * @throws DirectoryAlreadyExist
     * @throws FailedOnCopyFile
     * @throws FailedOnCreateDirectory
     * @throws PathNotFound
     */
    private function publishSetting(string $path): void
    {
        try {
            File::copy(
                $path,
                root_path() . '/settings',
                recursive: true,
                force: $this->input->getOption('force')
            );

            $this->successRow($path, 'PUBLISHED');
        } catch (TryCopyFileButAlreadyExists) {
            $this->warningRow($path, 'SKIPPED');
        }
    }

    protected function options(): array
    {
        return [
            Option::make(
                'force',
                'f',
                'Force publish setting and overwrite if exist.',
                OptionMode::None,
            ),
            Option::make(
                'provider',
                'p',
                'Publish settings from provider.',
                OptionMode::None,
            ),
            Option::make(
                'all',
                'a',
                'Publish all available settings.',
                OptionMode::None,
            ),
        ];
    }
}