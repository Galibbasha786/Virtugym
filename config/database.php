<?php

return [
    'default' => env('DB_CONNECTION', 'mongodb'),
    
    'connections' => [
        'mongodb' => [
            'driver' => 'mongodb',
            'dsn' => env('DB_URI', 'mongodb://127.0.0.1:27017'),
            'database' => env('DB_DATABASE', 'virtugym'),
            'options' => [
                'uid' => env('DB_USERNAME', ''),
                'pwd' => env('DB_PASSWORD', ''),
            ],
        ],
    ],
    
    'migrations' => 'migrations',
];