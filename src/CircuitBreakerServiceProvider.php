<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CircuitBreakerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-circuit-breaker')
            ->hasConfigFile();
    }
}
