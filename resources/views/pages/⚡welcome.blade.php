<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<main class="relative isolate flex min-h-screen flex-col items-center justify-center overflow-hidden bg-zinc-50 px-6 py-16 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-50">
    <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[40rem] bg-gradient-to-b from-zinc-200/60 via-transparent to-transparent dark:from-zinc-800/40"></div>

    <div class="flex w-full max-w-xl flex-col items-center gap-10 text-center">
        <div class="flex flex-col items-center gap-4">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/60 px-3 py-1 text-xs font-medium text-zinc-600 backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/60 dark:text-zinc-400">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Laravel {{ app()->version() }}
            </span>

            <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">
                {{ config('app.name') }}
            </h1>

            <p class="max-w-md text-balance text-base text-zinc-600 dark:text-zinc-400">
                NiftyCo's opinionated starter kit for Laravel &mdash; wired with Livewire, Tailwind, and Vite, ready to build.
            </p>
        </div>

        <livewire:counter />

        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-zinc-600 dark:text-zinc-400">
            <a href="https://laravel.com/docs" class="transition hover:text-zinc-900 dark:hover:text-zinc-100">Laravel</a>
            <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-700">&middot;</span>
            <a href="https://livewire.laravel.com/docs" class="transition hover:text-zinc-900 dark:hover:text-zinc-100">Livewire</a>
            <span aria-hidden="true" class="text-zinc-300 dark:text-zinc-700">&middot;</span>
            <a href="https://tailwindcss.com/docs" class="transition hover:text-zinc-900 dark:hover:text-zinc-100">Tailwind</a>
        </div>
    </div>
</main>
