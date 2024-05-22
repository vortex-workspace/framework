<?php

namespace Stellar\Vortex\Cosmo\Command;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Helpers\ArrayTool;
use Stellar\Vortex\Helpers\StrTool;

class StringWrapper
{
    private const string FOREGROUND_KEY = 'fg';
    private const string BACKGROUND_KEY = 'bg';
    private const string OPTIONS_KEY = 'options';

    private ConsoleStyleColor $backgroundColor;
    private ConsoleStyleColor $foregroundColor;
    /** @var ConsoleStyleOption[] $options */
    private array $options;

    public function __construct(private string $string = '')
    {
    }

    /**
     * @param ConsoleStyleColor $color
     * @return $this
     */
    public function background(ConsoleStyleColor $color): static
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * @param ConsoleStyleColor $color
     * @return $this
     */
    public function foreground(ConsoleStyleColor $color): static
    {
        $this->foregroundColor = $color;

        return $this;
    }

    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param ConsoleStyleOption[] $options
     * @return $this
     */
    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setString(string $string): static
    {
        $this->string = $string;

        return $this;
    }

    public function wrap(): string
    {
        $wrapped_string = '';

        if (isset($this->foregroundColor)) {
            $wrapped_string .= self::FOREGROUND_KEY . "={$this->foregroundColor->value};";
        }

        if (isset($this->backgroundColor)) {
            $wrapped_string .= self::BACKGROUND_KEY . "={$this->backgroundColor->value};";
        }

        if (isset($this->options)) {
            $options = [];

            foreach ($this->options as $key => $option) {
                $options[$key] = $option->value;
            }

            $wrapped_string .= self::OPTIONS_KEY
                . '='
                . ArrayTool::toString($options, ',', '', '')
                . ';';
        }

        if (StrTool::finishWith($wrapped_string, ';')) {
            $wrapped_string = substr($wrapped_string, 0, -1);
        }

        return "<$wrapped_string>$this->string</>";
    }
}
