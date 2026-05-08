<?php

use Livewire\Component;

new class extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        if ($this->count <= 0) {
            return;
        }

        $this->count--;
    }

    public function clear(): void
    {
        $this->count = 0;
    }
};
?>

<div class="flex w-full flex-col items-center gap-6 rounded-2xl border border-zinc-200 bg-white/70 p-8 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400">
        Live counter
    </p>

    <p class="text-7xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-50">
        {{ $count }}
    </p>

    <div class="flex items-center gap-2">
        <button
            type="button"
            wire:click="decrement"
            aria-label="Decrement"
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-lg font-medium text-zinc-700 transition hover:bg-zinc-50 active:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
        >
            &minus;
        </button>

        <button
            type="button"
            wire:click="clear"
            class="inline-flex h-10 items-center rounded-lg border border-zinc-200 bg-white px-4 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 active:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700"
        >
            Reset
        </button>

        <button
            type="button"
            wire:click="increment"
            aria-label="Increment"
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-900 text-lg font-medium text-white transition hover:bg-zinc-800 active:bg-zinc-700 dark:bg-zinc-50 dark:text-zinc-900 dark:hover:bg-zinc-200"
        >
            +
        </button>
    </div>
</div>
