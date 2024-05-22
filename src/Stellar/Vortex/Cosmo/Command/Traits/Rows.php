<?php

namespace Stellar\Vortex\Cosmo\Command\Traits;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\StringWrapper;

trait Rows
{
    public function successRow(string $target, string $status): void
    {
        $this->writeRow(
            $target,
            $status,
            (new StringWrapper())->foreground(ConsoleStyleColor::Green)->options([ConsoleStyleOption::Bold]),
            (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
            (new StringWrapper())->foreground(ConsoleStyleColor::Green)->options([ConsoleStyleOption::Bold])
        );
    }

    public function failRow(string $target, string $status): void
    {
        $this->writeRow(
            $target,
            $status,
            (new StringWrapper())->foreground(ConsoleStyleColor::Red)->options([ConsoleStyleOption::Bold]),
            (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
            (new StringWrapper())->foreground(ConsoleStyleColor::Red)->options([ConsoleStyleOption::Bold])
        );
    }

    public function warningRow(string $target, string $status): void
    {
        $this->writeRow(
            $target,
            $status,
            (new StringWrapper())->foreground(ConsoleStyleColor::Yellow)->options([ConsoleStyleOption::Bold]),
            (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
            (new StringWrapper())->foreground(ConsoleStyleColor::Yellow)->options([ConsoleStyleOption::Bold])
        );
    }

    public function debugRow(string $target, string $status): void
    {
        $this->writeRow(
            $target,
            $status,
            (new StringWrapper())->foreground(ConsoleStyleColor::Blue)->options([ConsoleStyleOption::Bold]),
            (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
            (new StringWrapper())->foreground(ConsoleStyleColor::Blue)->options([ConsoleStyleOption::Bold])
        );
    }

    public function indexRow(string $index, string $status = 'status'): void
    {
        if (!$this->display_index) {
            $this->display_index = true;
            $this->breakLine();
            $this->writeRow(
                $index,
                strtoupper($status),
                (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
                (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
                (new StringWrapper())->foreground(ConsoleStyleColor::Gray)
            );
            $this->breakLine(2);
        }
    }

    public function invalidRow(string $target, string $status): void
    {
        $this->writeRow(
            $target,
            $status,
            (new StringWrapper())->foreground(ConsoleStyleColor::BrightMagenta)->options([ConsoleStyleOption::Bold]),
            (new StringWrapper())->foreground(ConsoleStyleColor::Gray),
            (new StringWrapper())->foreground(ConsoleStyleColor::BrightMagenta)->options([ConsoleStyleOption::Bold])
        );
    }

    private function writeRow(
        string        $target,
        string        $status,
        StringWrapper $targetWrapper,
        StringWrapper $pointsWrapper,
        StringWrapper $statusWrapper
    ): void
    {
        $this->write(
            "    {$targetWrapper->setString($target)->wrap()} "
            . $pointsWrapper->setString($this->retrieveLoadPoints(10 + strlen($target . $status)))
                ->wrap()
            . " {$statusWrapper->setString(strtoupper($status))->wrap()}    ");
    }

    private function retrieveLoadPoints(int $non_point_string): string
    {
        return str_repeat('.', exec('tput cols') - $non_point_string);
    }
}
