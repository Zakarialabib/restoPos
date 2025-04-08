<?php

declare(strict_types=1);

return [
    'host' => env('REVERB_SERVER_HOST', '127.0.0.1'),
    'port' => env('REVERB_SERVER_PORT', 8080),
    'scheme' => env('REVERB_SERVER_SCHEME', 'http'),
    'allowed_origins' => '[*]',
    'options' => [
        'retry_after' => 3000,
        'enable_statistics' => true,
    ],

    'channels' => [
        'dashboard' => [
            'capacity' => 1000,
            'storage' => 'redis',
        ],
        'orders.*' => [
            'capacity' => 100,
            'storage' => 'redis',
        ],
        'inventory' => [
            'capacity' => 100,
            'storage' => 'redis',
        ],
    ],
];
