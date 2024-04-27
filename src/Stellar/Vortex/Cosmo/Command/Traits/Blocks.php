<?php

namespace Stellar\Vortex\Cosmo\Command\Traits;

use Exception;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

trait Blocks
{
    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function blackBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Black, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function blueBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Blue, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightBlueBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightBlue, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightCyanBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::Black,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightCyan, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightGreenBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightGreen, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightMagentaBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightMagenta, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightRedBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightRed, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightWhiteBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::Black,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightWhite, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function brightYellowBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::Black,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::BrightYellow, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function cyanBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Cyan, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function debuggerBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Blue, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function failBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Red, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function grayBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Gray, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function greenBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Green, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function magentaBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Magenta, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function redBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Red, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function successBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Green, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function warningBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Yellow, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function whiteBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::Black,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::White, $options, $large_block, $break_line);
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleOption[] $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    protected function yellowBlock(
        string            $message,
        ConsoleStyleColor $foreground = ConsoleStyleColor::BrightWhite,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $this->block($message, $foreground, ConsoleStyleColor::Yellow, $options, $large_block, $break_line);
    }

    /**
     * @param string|array $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleColor $background
     * @param array $options
     * @param bool $large_block
     * @param bool $break_line
     * @return void
     */
    private function block(
        string|array      $message,
        ConsoleStyleColor $foreground,
        ConsoleStyleColor $background,
        array             $options = [],
        bool              $large_block = true,
        bool              $break_line = true,
    ): void
    {
        $custom_style_name = 'custom_fg_' . $foreground->value . '_bkg_' . $background->value;

        if (!$this->output->getFormatter()->hasStyle($custom_style_name)) {
            foreach ($options as $index => $option) {
                if ($option instanceof ConsoleStyleOption) {
                    $options[$index] = $option->value;
                }
            }

            $this->output->getFormatter()->setStyle(
                $custom_style_name,
                new OutputFormatterStyle($foreground->value, $background->value, $options)
            );
        }

        if ($break_line) {
            $this->output->writeln($this->getHelper('formatter')
                ->formatBlock($message, $custom_style_name, $large_block));
        } else {
            $this->output->write($this->getHelper('formatter')
                ->formatBlock($message, $custom_style_name, $large_block));
        }
    }

    private function exceptionBlock(Exception $exception): void
    {
        $this->breakLine();
        $this->failBlock($exception->getMessage());
        $this->write(
            $exception->getFile() . ', Line: ' . $exception->getLine(),
            ConsoleStyleColor::Gray
        );
        $this->breakLine(2);
    }
}
