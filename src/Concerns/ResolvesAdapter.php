<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Concerns;

use HossamHoussien\CircuitBreaker\Adapters\RedisAdapter;
use HossamHoussien\CircuitBreaker\Contracts\AdapterInterface;
use RuntimeException;

trait ResolvesAdapter
{
    protected AdapterInterface $adapter;

    protected function resolveAdapter(): AdapterInterface
    {
        $adapter = strtolower($this->getSetting('adapter'));

        $adapterConfig = $this->getAdapterConfig($adapter);

        return match ($adapter) {
            'redis' => new RedisAdapter($adapterConfig),
            default => throw new RuntimeException('Invalid adapter'),
        };
    }
}
