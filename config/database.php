<?php

return [

    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql_backup' => [
            'driver' => 'mysql',
            'host' => env('BACKUP_DB_HOST', '127.0.0.1'),
            'port' => env('BACKUP_DB_PORT', '3306'),
            'database' => env('BACKUP_DB_DATABASE', 'backup_laravel'),
            'username' => env('BACKUP_DB_USERNAME', 'root'),
            'password' => env('BACKUP_DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
        ],

    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

];