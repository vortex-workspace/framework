<?php

namespace Stellar\Cosmo;

use Exception;
use Stellar\Boot\Application;
use Symfony\Component\Console\Application as SymfonyApplication;

class Cosmo
{
    private array $commands = [];
    private SymfonyApplication $symfonyApplication;

    /**
     * @return void
     * @throws Exception
     */
    public static function boot(): void
    {
        (new self())->symfonyApplication->run();
    }

    public function __construct()
    {
        $this->symfonyApplication = new SymfonyApplication('cosmo');

        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        $this->commands = Application::getInstance()->getCommands();

        if (!empty($this->commands)) {
            foreach ($this->commands as $command) {
                $this->symfonyApplication->add(new $command);
            }
        }
    }
}