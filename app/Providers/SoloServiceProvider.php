<?php

namespace App\Providers;

use AaronFrancis\Solo\Facades\Solo;
use AaronFrancis\Solo\Providers\SoloApplicationServiceProvider;

class SoloServiceProvider extends SoloApplicationServiceProvider
{
    public function register()
    {
        Solo::useTheme(config('solo.theme'))
            ->addCommands(config('solo.commands'))
            ->addLazyCommands(config('solo.lazyCommands'))
            ->allowCommandsAddedFrom(config('solo.allowedCommands'));
    }

    public function boot()
    {
        //
    }
}
