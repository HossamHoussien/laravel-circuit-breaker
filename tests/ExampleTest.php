<?php

declare(strict_types=1);

use HossamHoussien\CircuitBreaker\CircuitBreaker;

it('can test', function () {
    config([
        'circuit-breaker.open_timeout' => 'redis',
    ]);

    $circuitBreaker = new CircuitBreaker('test');

    $circuitBreaker->failure();
    $circuitBreaker->failure();
    $circuitBreaker->failure();

    dd([
        'isOpened' => $circuitBreaker->isOpened(),
        'isHalfOpened' => $circuitBreaker->isHalfOpened(),
        'isClosed' => $circuitBreaker->isClosed(),
        'getFailureCount' => $circuitBreaker->getFailureCount(),
    ]);
});
