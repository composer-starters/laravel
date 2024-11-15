<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Welcome to the Skeletor setup wizard!');

    $skeletor->spin('Setting up .env file', function () use ($skeletor) {
        sleep(1);
        $skeletor->exec(['cp', '.env.example', '.env']);
    });
    $skeletor->info('✔ Set up .env file');

    $skeletor->spin('Generating application key', function () use ($skeletor) {
        sleep(1);
        $skeletor->exec(['php', 'artisan', 'key:generate', '--ansi']);
    });
    $skeletor->info('✔ Generated application key');

    if ($skeletor->confirm('Would you like to run the database migrations?', true)) {
        $skeletor->spin('Running database migrations', function () use ($skeletor) {
            $skeletor->writeFile('database/database.sqlite', '');
            sleep(1);
            $skeletor->exec(['php', 'artisan', 'migrate', '--graceful', '--ansi']);
        });
        $skeletor->info('✔ Ran database migrations');
    }
};
