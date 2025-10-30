<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Concerns;

use RuntimeException;

trait ResolvesSettings
{
    protected ?array $settings = null;

    protected function resolveSettingsFromConfig(): array
    {
        return array_merge(
            $this->getDefaultConfig(),
            $this->getServiceConfig()
        );
    }

    protected function getSetting(string $name): mixed
    {
        if (is_null($this->settings)) {
            $this->settings = $this->resolveSettingsFromConfig();
        }

        if (! array_key_exists($name, $this->settings)) {
            throw new RuntimeException("`$name` configuration is missing");
        }

        return $this->settings[$name] ?? null;
    }

    protected function getAdapterConfig(string $adapter): array
    {
        return config("circuit-breaker.adapters.$adapter");
    }

    protected function getDefaultConfig(): array
    {
        return config('circuit-breaker.default') ?? [];
    }

    protected function getServiceConfig(): array
    {
        $service = trim(strtolower($this->service));

        return config("circuit-breaker.services.$service") ?? [];
    }
}
