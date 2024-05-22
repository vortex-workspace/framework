<?php

namespace Stellar\Cosmo\Commands;

use Stellar\Cosmo\Command;
use Stellar\Cosmo\Command\Enums\CommandReturnStatus;
use Stellar\Router;

class RouteList extends Command
{
    protected function handle(): CommandReturnStatus
    {
        dd(Router::getInstance()->getRoutes());
        return CommandReturnStatus::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Run to show all routes available in our application.');
    }

    protected function name(): string
    {
        return 'route:list';
    }
}
