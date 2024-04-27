<?php

namespace Stellar\Core\Cosmo\Commands;

use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\StringWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use const Core\Cosmo\Commands\ROUTES;

#[AsCommand(
    name: 'route:list',
    description: 'This command list all routes available in our application.'
)]
class RouteList extends VortexCommand
{
    protected function handle(): CommandReturnStatus
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['ROUTE_LIST_CALL'] = true;

        include __DIR__ . '/../../../../../../Routes/routes.php';

        $rows = [];

        foreach (ROUTES as $route) {
            $has_controller = is_array($route['path_to_include']) && is_string($route['path_to_include'][0]);
            $has_name = isset($route['name']) && is_string($route['name']);

            $rows[] = [
                (new StringWrapper($route['method']))
                    ->foreground(ConsoleStyleColor::BRIGHT_BLUE)
                    ->options([ConsoleStyleOption::BOLD])
                    ->wrap(),
                (new StringWrapper($route['route']))
                    ->foreground(ConsoleStyleColor::WHITE)
                    ->options([ConsoleStyleOption::BOLD])
                    ->wrap(),
                (new StringWrapper($has_controller ? $route['path_to_include'][0] : 'undefined'))
                    ->foreground($has_controller ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                    ->options([ConsoleStyleOption::BOLD])
                    ->wrap(),
                (new StringWrapper($has_controller ? $route['path_to_include'][1] : 'undefined'))
                    ->foreground($has_controller ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                    ->options([ConsoleStyleOption::BOLD])
                    ->wrap(),
                (new StringWrapper($has_name ? $route['name'] : 'undefined'))
                    ->foreground($has_name ? ConsoleStyleColor::WHITE : ConsoleStyleColor::GRAY)
                    ->options([ConsoleStyleOption::BOLD])
                    ->wrap(),
            ];
        }

        $this->breakLine();
        $this->defaultTable([
            (new StringWrapper('METHOD'))->foreground(ConsoleStyleColor::GREEN)->options([ConsoleStyleOption::BOLD])->wrap(),
            (new StringWrapper('ROUTE'))->foreground(ConsoleStyleColor::GREEN)->options([ConsoleStyleOption::BOLD])->wrap(),
            (new StringWrapper('CONTROLLER'))->foreground(ConsoleStyleColor::GREEN)->options([ConsoleStyleOption::BOLD])->wrap(),
            (new StringWrapper('FUNCTION'))->foreground(ConsoleStyleColor::GREEN)->options([ConsoleStyleOption::BOLD])->wrap(),
            (new StringWrapper('NAME'))->foreground(ConsoleStyleColor::GREEN)->options([ConsoleStyleOption::BOLD])->wrap(),
        ], $rows)->render();
        $this->breakLine();

        return CommandReturnStatus::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run route:list to show all routes available in our application.');
    }
}
