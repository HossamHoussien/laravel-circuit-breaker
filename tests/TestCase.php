<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Tests;

use HossamHoussien\CircuitBreaker\CircuitBreakerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CircuitBreakerServiceProvider::class,
        ];
    }
}
