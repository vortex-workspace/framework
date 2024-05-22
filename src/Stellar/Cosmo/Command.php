<?php

namespace Stellar\Cosmo;

use Error;
use Exception;
use Stellar\Boot\Application;
use Stellar\Core\Contracts\CommandInterface;
use Stellar\Cosmo\Command\Enums\CommandReturnStatus;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Cosmo\Command\Exceptions\InvalidOutputStyle;
use Stellar\Cosmo\Command\OutputStyle;
use Stellar\Cosmo\Command\OutputStyles\BlueBkg;
use Stellar\Cosmo\Command\OutputStyles\BrightBlueBkg;
use Stellar\Cosmo\Command\OutputStyles\BrightGreen;
use Stellar\Cosmo\Command\OutputStyles\Cyan;
use Stellar\Cosmo\Command\OutputStyles\Gray;
use Stellar\Cosmo\Command\OutputStyles\GreenBkg;
use Stellar\Cosmo\Command\OutputStyles\Red;
use Stellar\Cosmo\Command\OutputStyles\RedBkg;
use Stellar\Cosmo\Command\OutputStyles\YellowBkg;
use Stellar\Cosmo\Command\Traits\Bells;
use Stellar\Cosmo\Command\Traits\Blocks;
use Stellar\Cosmo\Command\Traits\Defaults;
use Stellar\Cosmo\Command\Traits\Permissions;
use Stellar\Cosmo\Command\Traits\Rows;
use Stellar\Cosmo\Command\Traits\Write;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Settings\Setting;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand implements CommandInterface
{
    use Bells;
    use Blocks;
    use Defaults;
    use Permissions;
    use Rows;
    use Write;

    protected InputInterface $input;
    protected OutputInterface $output;

    abstract protected function name(): string;

    abstract protected function handle(): CommandReturnStatus;

    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $this->setName($this->name());
        }

        if (!static::can(Application::getInstance()) || !static::canSee(Application::getInstance())) {
            $this->setHidden();
        }

        foreach ($this->arguments() as $argument) {
            $this->registerParameterByType($argument);
        }

        foreach ($this->options() as $option) {
            $this->registerParameterByType($option, false);
        }

        parent::__construct($name);
    }

    /**
     * @return array<Argument>
     */
    protected function arguments(): array
    {
        return [];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws InvalidOutputStyle
     * @throws InvalidSettingException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandResult = CommandReturnStatus::FAILED;
        $this->startCommand($input, $output);

        if (self::can(Application::getInstance()) && self::canRun(Application::getInstance())) {
            try {
                $this->setRuntimeStart();

                $commandResult = $this->handle();
            } catch (Error|Exception $exception) {
                $this->exceptionBlock($exception);
            }
        }

        $this->endCommand($commandResult);

        return $commandResult->value;
    }

    /**
     * @return array<Option>
     */
    protected function options(): array
    {
        return [];
    }

    private function endCommand(CommandReturnStatus $status): void
    {
        $this->writeCommandEnd($status);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws InvalidOutputStyle
     * @throws InvalidSettingException
     */
    private function startCommand(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
        $this->loadStyles();
        $this->writeCommandStart();
    }

    /**
     * @param Option|Argument $parameter
     * @param bool $is_argument
     * @return void
     */
    private function registerParameterByType(Option|Argument $parameter, bool $is_argument = true): void
    {
        if ($is_argument && $parameter instanceof Argument) {
            $this->addArgument(...$parameter->toArray());

            return;
        }

        if ($parameter instanceof Option) {
            $this->addOption(...$parameter->toArray());
        }
    }

    /**
     * @return void
     * @throws InvalidOutputStyle
     * @throws InvalidSettingException
     */
    private function loadStyles(): void
    {
        foreach ($this->defaultStyles() as $style) {
            $this->loadStyle($style);
        }

        foreach (Setting::get(SettingKey::COSMO_STYLES->value, []) as $style) {
            $this->loadStyle($style);
        }
    }

    /**
     * @param string $style
     * @return void
     * @throws InvalidOutputStyle
     */
    private function loadStyle(string $style): void
    {
        if (!(($style = new $style) instanceof OutputStyle)) {
            throw new InvalidOutputStyle;
        }

        foreach (($options = $style->options()) as $index => $option) {
            if ($option instanceof ConsoleStyleOption) {
                $options[$index] = $option->value;
            }
        }

        $this->output->getFormatter()->setStyle(
            $style->name(),
            new OutputFormatterStyle($style->foreground()->value, $style->background()?->value, $options)
        );
    }

    private function defaultStyles(): array
    {
        return [
            BlueBkg::class,
            BrightBlueBkg::class,
            BrightGreen::class,
            Cyan::class,
            Gray::class,
            GreenBkg::class,
            Red::class,
            RedBkg::class,
            YellowBkg::class,
        ];
    }
}