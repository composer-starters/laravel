<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Welcome to the Skeletor setup wizard!');

    $name = $skeletor->text(
        label: 'What is the name of your application?',
        placeholder: 'E.g. example-app',
        required: 'Your application name is required.',
        default: $skeletor->workspace,
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
            $skeletor->copyFile('.env.example', '.env');
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

    $frontend = $skeletor->select('Which front-end framework would you like to use?', [
        'none' => 'None',
        'react' => 'Inertia with React',
        'vue' => 'Inertia with Vue',
        'livewire' => 'Livewire',
    ], 'none');

    if ($frontend !== 'none') {
        $deps = [
            'npm' => [
                'vite-plugin-ziggy',
            ],
            'composer' => [
                'tightenco/ziggy',
            ],
        ];

        [$type, $callback] = match ($frontend) {
            'react' => ['Inertia with React', function () use ($skeletor, $deps) {
                $skeletor->exec(['composer', 'require', 'inertiajs/inertia-laravel', ...$deps['composer']]);
                $skeletor->exec(['npm', 'install', 'react', 'react-dom', '@inertiajs/react']);
                $skeletor->exec(['npm', 'install', '--save-dev', '@types/react', '@types/react-dom', '@vitejs/plugin-react', ...$deps['npm']]);
                $skeletor->exec(['php', 'artisan', 'inertia:middleware']);
                $skeletor->removeFile('resources/client/app.ts');
                $skeletor->writeFile('resources/client/app.tsx', $skeletor->readFile('.github/stubs/react.app.stub'));
                $skeletor->writeFile('resources/views/app.blade.php', $skeletor->readFile('.github/stubs/react.view.stub'));
                $skeletor->removeFile('vite.config.ts');
                $skeletor->writeFile('vite.config.ts', $skeletor->readFile('.github/stubs/react.vite.stub'));
            }],
            'vue' => ['Inertia with Vue', function () use ($skeletor, $deps) {
                $skeletor->exec(['composer', 'require', 'inertiajs/inertia-laravel', ...$deps['composer']]);
                $skeletor->exec(['npm', 'install', 'vue', '@inertiajs/vue3']);
                $skeletor->exec(['npm', 'install', '--save-dev', '@vitejs/plugin-vue', ...$deps['npm']]);
                $skeletor->exec(['php', 'artisan', 'inertia:middleware']);
                $skeletor->removeFile('resources/client/app.ts');
                $skeletor->writeFile('resources/client/app.ts', $skeletor->readFile('.github/stubs/vue.app.stub'));
                $skeletor->writeFile('resources/views/app.blade.php', $skeletor->readFile('.github/stubs/vue.view.stub'));
                $skeletor->removeFile('vite.config.ts');
                $skeletor->writeFile('vite.config.ts', $skeletor->readFile('.github/stubs/vue.vite.stub'));
            }],
            'livewire' => ['Livewire', function () use ($skeletor) {
                $skeletor->exec(['composer', 'require', 'livewire/livewire']);
            }],
        };

        $skeletor->spin(
            message: "Installing $type",
            success: "$type installed.",
            error: "Failed to install $type.",
            callback: $callback
        );
    }

    $origin = $skeletor->text(
        label: 'What is your git repository remote?',
        placeholder: 'E.g. git@github.com:example/example-app.git',
        required: false
    );

    return function () use ($skeletor, $origin) {
        if (! empty($origin)) {
            $skeletor->spin(
                message: 'Setting up git remote...',
                success: 'Git remote set.',
                error: 'Failed to set up git remote.',
                callback: function () use ($skeletor, $origin) {
                    $skeletor->exec(['git', 'init']);
                    $skeletor->exec(['git', 'remote', 'add', 'origin', $origin]);
                    $skeletor->exec(['git', 'add', '-A']);
                    $skeletor->exec(['git', 'commit', '-m', '"initial commit"']);
                }
            );
        }

        $skeletor->outro('🎉 Your Laravel application is ready to go!');
        $skeletor->log('To get started run the following commands:');
        $skeletor->log(' - '.$skeletor->cyan('cd '.$skeletor->workspace));
        $skeletor->log(' - '.$skeletor->cyan('php artisan solo'));
    };
};
