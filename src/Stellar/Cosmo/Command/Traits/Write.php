<?php

namespace Stellar\Cosmo\Command\Traits;

use Carbon\Carbon;
use Stellar\Cosmo\Command\Enums\CommandReturnStatus;
use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Helpers\ArrayTool;

trait Write
{
    public function breakLine(int $times = 1): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->output->writeln('');
        }
    }

    /**
     * @param CommandReturnStatus $result
     * @return void
     */
    private function writeCommandEnd(CommandReturnStatus $result): void
    {
        switch ($result) {
            case CommandReturnStatus::SUCCESS:
                $this->blueBlock($this->getName(), options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                $this->successBlock('Success', options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                break;
            case CommandReturnStatus::FAILED:
                $this->blueBlock($this->getName(), options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                $this->failBlock('Failed', options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                break;
            case CommandReturnStatus::INVALID:
                $this->blueBlock($this->getName(), options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                $this->warningBlock('Warning', options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
                break;
        }

        if (($debugger_response = $this->mountDebuggerString()) !== null) {
            $this->write($debugger_response, ConsoleStyleColor::BrightMagenta);
        }

        $this->write(
            'Vortex',
            ConsoleStyleColor::BrightCyan,
            null,
            ConsoleStyleOption::Bold,
            break_line: true
        );
    }

    private function mountDebuggerString(): ?string
    {
        $debugger_message = null;

        if ($this->withRuntime()) {
            $duration = Carbon::now()->getTimestampMs() - $this->runtimeStart->getTimestampMs();
            $debugger_message = "({$duration}ms";
        }

        if ($this->changes !== null) {
            $change_label = 'change';

            if ($this->changes > 1) {
                $change_label = 'changes';
            }

            if ($debugger_message !== null) {
                $debugger_message .= ", $this->changes $change_label";
            } else {
                $debugger_message = "($this->changes $change_label";
            }
        }

        if ($debugger_message === null) {
            return null;
        }

        return " $debugger_message) ";
    }

    /**
     * @param string $message
     * @param ConsoleStyleColor $foreground
     * @param ConsoleStyleColor|null $background
     * @param ConsoleStyleOption|ConsoleStyleOption[] $options
     * @param string|null $custom_style
     * @param bool $break_line
     * @return void
     */
    public function write(
        string                   $message,
        ConsoleStyleColor        $foreground = ConsoleStyleColor::BrightWhite,
        ?ConsoleStyleColor       $background = null,
        ConsoleStyleOption|array $options = [],
        ?string                  $custom_style = null,
        bool                     $break_line = false
    ): void
    {
        if ($custom_style) {
            $this->output->write("<$custom_style>$message</>");
            return;
        }

        if (is_array($options)) {
            foreach ($options as $key => $option) {
                $options[$key] = $option->value;
            }

            $options = ArrayTool::toString($options, ',', '', '');
        } else {
            $options = $options->value;
        }

        $background_string = '';

        if ($background) {
            $background_string = "bg=$background->value;";
        }

        $content = "<fg=$foreground->value;{$background_string}options=$options>$message</>";

        if ($break_line) {
            $this->output->writeln($content);
            return;
        }

        $this->output->write($content);
    }

    private function writeCommandStart(): void
    {
        $this->blueBlock($this->getName(), options: [ConsoleStyleOption::Bold], large_block: false, break_line: false);
        $this->brightCyanBlock(
            'Vortex',
            ConsoleStyleColor::Gray,
            [ConsoleStyleOption::Bold],
            false
        );
    }
}
