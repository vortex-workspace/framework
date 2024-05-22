<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Helpers\FileDirManager;
use Core\Helpers\Hash;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'key:generate',
)]
class GenerateProjectKey extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        $env_path = Path::fullPath(ProjectPath::ROOT->additionalPath('.env'));
        $env_file_content = file_get_contents($env_path);

        if (preg_match('/APP_KEY=\w{10,}/', $env_file_content)) {
            $this->breakLine();
            $this->warningBlock('Our project .env already has Application Key');
            $this->breakLine();
            return CommandReturnStatus::SUCCESS;
        }

        $app_key = 'APP_KEY=' . Hash::sha512(uniqid(random_bytes(20), true));

        if (!FileDirManager::updateFile($env_path, ['APP_KEY=' => $app_key])) {
            if (!FileDirManager::addingInFinalOfFile($env_path, $app_key)) {
                $this->breakLine();
                $this->failBlock('Missing .env file');
                $this->breakLine();
                return CommandReturnStatus::FAILED;
            };
        }

        $this->breakLine();
        $this->successBlock('Application key successfully created');
        $this->breakLine();
        return CommandReturnStatus::SUCCESS;
    }
}
