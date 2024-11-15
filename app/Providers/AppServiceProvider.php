<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use AaronFrancis\Solo\Facades\Solo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the Solo configuration
        Solo::useTheme(config('solo.theme'))
            ->addCommands(config('solo.commands'))
            ->addLazyCommands(config('solo.lazyCommands'))
            ->allowCommandsAddedFrom(config('solo.allowedCommands'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
