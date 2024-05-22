<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\ProjectPath;
use Core\Database\migrations\MigrationsTable;
use Core\Helpers\ConsoleTool;
use Core\Structure\Path;
use Dotenv\Dotenv;
use Exception;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'vortex:install',
    description: 'This command install Vortex.'
)]
class VortexInstall extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        $this->loadEnvironment();
        $this->runFirstsMigrations();
        $this->setDefaultTimeZone();
        $this->composerInstall();
        $this->npmInstall();
        $this->npmCompile();

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run vortex:install to set the first configurations in your project.');
    }

    private function runFirstsMigrations(): void
    {
        try {
            $this->write('Create "migrations" table...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
            MigrationsTable::up();
        } catch (Exception) {
            $this->write('Already exist, skipped!', break_line: true);
        }
    }

    private function loadEnvironment(): void
    {
        $this->write('Load environment variables...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
        $env = Dotenv::createImmutable(Path::fullPath(ProjectPath::ROOT->value));
        $env->load();
        $this->write('Environment loaded.', break_line: true);
    }

    private function setDefaultTimeZone(): void
    {
        $this->write('Set default timezone...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
        date_default_timezone_set($timezone = ($_ENV['TIME_ZONE'] ?? 'America/Sao_Paulo'));
        $this->write("Setted to \"$timezone\"", break_line: true);
    }

    private function npmInstall(): void
    {
        $this->write('Install NPM dependecies...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
        ConsoleTool::call('npm install', Path::fullPath(ProjectPath::ROOT->value));
        $this->write('NPM installed.', break_line: true);
    }

    private function npmCompile(): void
    {
        $this->write('Compiling assets...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
        ConsoleTool::call('npm run vortex', Path::fullPath(ProjectPath::ROOT->value));
        $this->write('Finish compilation.', break_line: true);
    }

    private function composerInstall(): void
    {
        $this->write('Install Composer dependencies...', ConsoleStyleColor::BRIGHT_BLUE, break_line: true);
        ConsoleTool::call('composer install', Path::fullPath(ProjectPath::ROOT->value));
        $this->write('Finish install.', break_line: true);
    }
}
