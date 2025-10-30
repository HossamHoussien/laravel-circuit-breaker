<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Facades;

use HossamHoussien\CircuitBreaker\CircuitBreaker as CircuitBreakerClass;
use Illuminate\Support\Facades\Facade;

/**
 * @see CircuitBreakerClass
 */
class CircuitBreaker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CircuitBreakerClass::class;
    }
}
