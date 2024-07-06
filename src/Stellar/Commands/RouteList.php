<?php

namespace Stellar\Commands;

use Cosmo\Command;
use Cosmo\Command\Enums\CommandResponse;
use Cosmo\Command\Enums\ConsoleStyleColor;
use Cosmo\Command\StringWrapper;
use Stellar\Route;
use Stellar\Route\Enums\HttpMethod;
use Stellar\Router;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

class RouteList extends Command
{
    protected function name(): string
    {
        return 'route:list';
    }

    public function setDescription(string $description): static
    {
        return parent::setDescription('This command list all routes available in our application.');
    }

    /**
     * @return CommandResponse
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     */
    protected function handle(): CommandResponse
    {
        $this->table($this->getTableHeader(), $this->formatRoutes());

        return CommandResponse::SUCCESS;
    }

    /**
     * @return array
     * @throws PrefixIsEnabledButNotFound
     * @throws InvalidSettingException
     */
    private function formatRoutes(): array
    {
        $formatedRoutes = [];

        foreach (Router::getInstance()->getRoutes() as $method => $routes) {
            /** @var Route $configuration */
            foreach ($routes as $uri => $configuration) {
                $formatedRoutes[] = [
                    'method' => $this->formatHttpMethodRow($method),
                    'name' => $configuration->getName(),
                    'uri' => $uri,
                    'controller' => $configuration->getController(),
                    'action' => $configuration->getMethod() === null ? '-' : $configuration->getMethod() . '()',
                    'group' => $configuration->getOriginGroup(),
                ];
            }
        }

        return $formatedRoutes;
    }

    private function formatHttpMethodRow(string $method): string
    {
        return (new StringWrapper($method))->foreground(match ($method) {
            HttpMethod::GET->name => ConsoleStyleColor::BrightGreen,
            HttpMethod::POST->name, HttpMethod::PUT->name, HttpMethod::PATCH->name => ConsoleStyleColor::BrightBlue,
            HttpMethod::DELETE->name => ConsoleStyleColor::BrightRed,
            HttpMethod::HEAD->name, HttpMethod::CONNECT->name, HttpMethod::OPTIONS->name, HttpMethod::TRACE->name => ConsoleStyleColor::BrightYellow,
        })->wrap();
    }

    private function getTableHeader(): array
    {
        return [
            (new StringWrapper('METHOD'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
            (new StringWrapper('NAME'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
            (new StringWrapper('URI'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
            (new StringWrapper('CONTROLLER'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
            (new StringWrapper('ACTION'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
            (new StringWrapper('GROUP'))->foreground(ConsoleStyleColor::BrightCyan)->wrap(),
        ];
    }
}
