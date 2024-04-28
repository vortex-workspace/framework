<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Helpers\ConsoleTool;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Helpers\Environment;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'vortex:server',
    description: 'This command start php server.'
)]
class VortexServer extends VortexCommand
{
    private const DEFAULT_SERVER_PORT = 8000;

    protected function handle(): CommandReturnStatus
    {
        $command = 'php -S localhost:'
        . Environment::appLocalhostServerPort() ?? self::DEFAULT_SERVER_PORT
        . ' -t '
        . Path::fullPath(ProjectPath::PUBLIC_ROOT->value);

        ConsoleTool::call($command, Path::fullPath(ProjectPath::PUBLIC_ROOT->value));

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run vortex:server to start the php server.');
    }
}
