<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Welcome to the Skeletor setup wizard!');

    $name = $skeletor->text(
        label: 'What is the name of your application?',
        placeholder: 'E.g. example-app',
        required: 'Your application name is required.',
        validate: function ($value) {
            if (preg_match('/[^\pL\pN\-_.]/', $value) !== 0) {
                return 'The name may only contain letters, numbers, dashes, underscores, and periods.';
            }
        }
    );

    $skeletor->spin(
        message: 'Setting up environment...',
        success: 'Environment set.',
        error: 'Failed to set up environment.',
        callback: function () use ($skeletor, $name) {
            $skeletor->exec(['cp', '.env.example', '.env']);
            $skeletor->exec(['php', 'artisan', 'key:generate', '--ansi']);
            $skeletor->replaceInFile('APP_NAME=Laravel', 'APP_NAME='.$name, '.env');
            $skeletor->replaceInFile('APP_NAME=Laravel', 'APP_NAME='.$name, '.env.example');
        }
    );

    $db = $skeletor->select('Which database would you like to use?', [
        'sqlite' => 'SQLite',
        'mysql' => 'MySQL',
        'pgsql' => 'PostgreSQL',
        'sqlsrv' => 'SQL Server',
    ], 'sqlite');

    $skeletor->spin(
        message: 'Configuring database...',
        success: 'Database configured.',
        error: 'Failed to configure database.',
        callback: function () use ($skeletor, $db, $name) {
            $skeletor->pregReplaceInFile('/DB_CONNECTION=.*/', 'DB_CONNECTION='.$db, '.env');
            $skeletor->pregReplaceInFile('/DB_CONNECTION=.*/', 'DB_CONNECTION='.$db, '.env.example');

            if ($db === 'sqlite') {
                $env = $skeletor->readFile('.env');

                if (! str_contains($env, '# DB_HOST=127.0.0.1')) {
                    $defaults = [
                        'DB_HOST=127.0.0.1',
                        'DB_PORT=3306',
                        'DB_DATABASE=laravel',
                        'DB_USERNAME=root',
                        'DB_PASSWORD=',
                    ];

                    $skeletor->replaceInFile($defaults, array_map(fn ($default) => '# '.$default, $defaults), '.env');
                    $skeletor->replaceInFile($defaults, array_map(fn ($default) => '# '.$default, $defaults), '.env.example');

                    return;
                }

                return;
            }

            $defaults = [
                '# DB_HOST=127.0.0.1',
                '# DB_PORT=3306',
                '# DB_DATABASE=laravel',
                '# DB_USERNAME=root',
                '# DB_PASSWORD=',
            ];

            $skeletor->replaceInFile($defaults, array_map(fn ($default) => substr($default, 2), $defaults), '.env');
            $skeletor->replaceInFile($defaults, array_map(fn ($default) => substr($default, 2), $defaults), '.env.example');

            $defaultPorts = [
                'pgsql' => '5432',
                'sqlsrv' => '1433',
            ];

            if (isset($defaultPorts[$db])) {
                $skeletor->replaceInFile('DB_PORT=3306', 'DB_PORT='.$defaultPorts[$db], '.env');
                $skeletor->replaceInFile('DB_PORT=3306', 'DB_PORT='.$defaultPorts[$db], '.env.example');
            }

            $skeletor->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.str_replace('-', '_', strtolower($name)), '.env');
            $skeletor->replaceInFile('DB_DATABASE=laravel', 'DB_DATABASE='.str_replace('-', '_', strtolower($name)), '.env.example');
        }
    );

    if ($skeletor->confirm('Would you like to run the database migrations?', true)) {
        $skeletor->spin(
            message: 'Running database migrations',
            success: 'Database migrated.',
            error: 'Failed to migrate database.',
            callback: function () use ($skeletor, $db) {
                if ($db === 'sqlite') {
                    $skeletor->writeFile('database/database.sqlite', '');
                }

                $skeletor->exec(['php', 'artisan', 'migrate', '--graceful', '--ansi']);
            }
        );
    }

    if ($skeletor->confirm('Would you like to install the front-end dependencies?', true)) {
        $skeletor->spin(
            message: 'Installing front-end dependencies',
            success: 'Front-end dependencies installed.',
            error: 'Failed to install front-end dependencies.',
            callback: function () use ($skeletor) {
                $skeletor->exec(['npm', 'install']);
            }
        );
    }

    $skeletor->outro('🎉 Your Laravel application is ready to go!');
    $skeletor->log('To get started run the following commands:');
    $skeletor->log(' - '.$skeletor->cyan('cd '.$skeletor->workspace));
    $skeletor->log(' - '.$skeletor->cyan('composer dev'));
};
