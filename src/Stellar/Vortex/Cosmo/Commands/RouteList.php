<?php

namespace Stellar\Vortex\Cosmo\Commands;

use Stellar\Vortex\Cosmo\Command;
use Stellar\Vortex\Cosmo\Command\Enums\CommandReturnStatus;

class RouteList extends Command
{
    protected function handle(): CommandReturnStatus
    {
        return CommandReturnStatus::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Run route:list to show all routes available in our application.');
    }

    #[\Override] protected function name(): string
    {
        return 'route:list';
    }
}
