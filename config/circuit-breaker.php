<?php

declare(strict_types=1);

return [
    'adapters' => [
        'redis' => [
            'connection' => 'default', // define the redis connection to use
        ],
    ],

    'default' => [
        'adapter' => (string) env('CIRCUIT_BREAKER_ADAPTER', 'redis'),

        'open_timeout' => (int) env('CIRCUIT_BREAKER_OPEN_TIMEOUT', 120),
        'half_open_timeout' => (int) env('CIRCUIT_BREAKER_HALF_OPEN_TIMEOUT', 120),

        // Open the circuit when reaching 9 failures within 600 seconds
        'failure_threshold' => (int) env('CIRCUIT_BREAKER_FAILURE_THRESHOLD', 1),
        'failure_interval' => (int) env('CIRCUIT_BREAKER_FAILURE_INTERVAL', 600),

        // Close the circuit when reaching 5 successes within 600 seconds
        'success_threshold' => (int) env('CIRCUIT_BREAKER_SUCCESS_THRESHOLD', 5),
        'success_interval' => (int) env('CIRCUIT_BREAKER_SUCCESS_INTERVAL', 600),

    ],

    /**
     * Overrides the default settings for specific services
     */
    'services' => [],
];
