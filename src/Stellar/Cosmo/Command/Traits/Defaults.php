<?php

namespace Stellar\Cosmo\Command\Traits;

use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ProgressBarMode;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;

trait Defaults
{
    public function defaultProgressBar(
        int             $steps,
        ProgressBarMode $format_mode = ProgressBarMode::NORMAL
    ): ProgressBar
    {
        $progressBar = new ProgressBar($this->output, $steps);
        $progressBar->setFormat($format_mode->value);

        return $progressBar;
    }

    public function defaultTable(array|string $headers, array $rows = []): Table
    {
        $tableStyle = (new TableStyle())
            ->setHorizontalBorderChars('<fg=' . ConsoleStyleColor::White->value . '>-</>')
            ->setVerticalBorderChars('<fg=' . ConsoleStyleColor::White->value . '>\</>')
            ->setDefaultCrossingChar('<fg=' . ConsoleStyleColor::White->value . '>+</>');

        if (!is_array($headers)) {
            $headers = [$headers];
        }

        return (new Table($this->output))->setHeaders($headers)->setRows($rows);
    }
}
