<?php


return [
    'default' => 'sqlite',
    'connections' => [
        'sqlite' => [
            'driver' => env('DB_CONNECTION', 'sqlite'),
            'database' => database_path(env('DB_DATABASE', 'database.sqlite')),
            'prefix' => '',
        ],
        'sqlite_testing' => [
            'driver' => env('DB_CONNECTION_TESTING', 'sqlite'),
            'database' => database_path(env('DB_DATABASE_TESTING', 'database_testing.sqlite')),
            'prefix' => '',
        ],
    ],
    'migrations' => 'migrations'
];
