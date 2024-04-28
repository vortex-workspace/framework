<?php

namespace Stellar\Cosmo\Command\Traits;

use Carbon\Carbon;
use Closure;

trait Bells
{
    protected ?int $changes = null;
    protected Carbon $runtimeStart;
    private bool $display_index = false;

    /**
     * @param Closure|bool $change
     * @return void
     */
    protected function addChange(Closure|bool $change = true): void
    {
        if (!is_bool($change)) {
            $change = (bool)$change();
        }

        if ($change) {
            $this->changes++;
        }
    }

    protected function withRuntime(): bool
    {
        return true;
    }

    private function setRuntimeStart(): void
    {
        $this->runtimeStart = Carbon::now();
    }
}