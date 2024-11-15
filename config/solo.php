<?php

use AaronFrancis\Solo\Commands\EnhancedTailCommand;

return [
    'theme' => 'dark',

    // Commands that auto start.
    'commands' => [
        EnhancedTailCommand::make('Logs', 'tail -f -n 100 '.storage_path('logs/laravel.log')),
        'Vite' => 'npm start --silent',
    ],

    // Not auto-started
    'lazyCommands' => [
        'HTTP' => 'php artisan serve',
        'Queue' => 'php artisan queue:listen --tries=1',
        'Scheduler' => 'php artisan schedule:work',
        'Reverb' => 'php artisan reverb:start',
        'Pint' => 'vendor/bin/pint --ansi',
    ],

    // FQCNs of trusted classes that can add commands.
    'allowedCommands' => [
        //
    ],
];
