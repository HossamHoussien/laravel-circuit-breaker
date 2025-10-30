<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker;

use HossamHoussien\CircuitBreaker\Concerns\ResolvesAdapter;
use HossamHoussien\CircuitBreaker\Concerns\ResolvesSettings;
use HossamHoussien\CircuitBreaker\Enums\Key;

class CircuitBreaker
{
    use ResolvesAdapter;
    use ResolvesSettings;

    public function __construct(private string $service)
    {
        $this->adapter = $this->resolveAdapter();

        $this->settings = $this->resolveSettingsFromConfig();
    }

    protected function resolveKey(string $key): string
    {
        return "circuit-breaker:$this->service:$key";
    }

    public function getFailureCount(): int
    {
        return (int) $this->adapter->get(
            $this->resolveKey(Key::FAILURES)
        ) ?? 0;
    }

    public function getSuccessCount(): int
    {
        return (int) $this->adapter->get(
            $this->resolveKey(Key::SUCCESSES)
        ) ?? 0;
    }

    public function incrementFailures(): int
    {
        $key = $this->resolveKey(Key::FAILURES);

        $ttl = $this->getSetting('failure_interval');

        return $this->adapter->increment($key, $ttl);
    }

    public function incrementSuccesses(): int
    {
        $key = $this->resolveKey(Key::SUCCESSES);

        $ttl = $this->getSetting('success_interval');

        return $this->adapter->increment($key, $ttl);
    }

    public function hasReachedFailureThreshold(): bool
    {
        $failureCount = $this->getFailureCount();

        $failureThreshold = $this->getSetting('failure_threshold');

        return $failureCount >= $failureThreshold;
    }

    public function hasReachedSuccessThreshold(): bool
    {
        $successCount = $this->getSuccessCount();

        $successThreshold = $this->getSetting('success_threshold');

        return $successCount >= $successThreshold;
    }

    public function isOpened(): bool
    {
        return (bool) $this->adapter->get(
            $this->resolveKey(Key::OPENED)
        );
    }

    public function isHalfOpened(): bool
    {
        if ($this->isOpened()) {
            return false;
        }

        return (bool) $this->adapter->get(
            $this->resolveKey(Key::HALF_OPENED)
        );
    }

    public function isClosed(): bool
    {
        return ! $this->isOpened() && ! $this->isHalfOpened();
    }

    public function failure(): void
    {
        // Circuit is already open-state, do nothing
        if ($this->isOpened()) {
            return;
        }

        // Open the circuit again on the first failure if the state is half-opened
        if ($this->isHalfOpened()) {
            $this->openCircuit();

            return;
        }

        $this->incrementFailures();

        // In closed-state, check if the failure threshold has been reached
        if ($this->hasReachedFailureThreshold()) {
            $this->openCircuit();
        }
    }

    public function success(): void
    {
        // Circuit is already in closed-state, do nothing
        if ($this->isClosed()) {
            $this->reset();

            return;
        }

        $this->incrementSuccesses();

        // Check if the success threshold has been reached
        if ($this->hasReachedSuccessThreshold()) {
            $this->reset();
        }
    }

    public function openCircuit(): void
    {
        $openKey = $this->resolveKey(Key::OPENED);
        $halfOpenKey = $this->resolveKey(Key::HALF_OPENED);

        // Record when the circuit was opened
        $value = time();

        $openTimeout = $this->getSetting('open_timeout');
        $halfOpenTimeout = $this->getSetting('half_open_timeout');

        $this->adapter->set($openKey, $value, $openTimeout);
        $this->adapter->set($halfOpenKey, $value, $openTimeout + $halfOpenTimeout);
    }

    public function reset(): void
    {
        $this->adapter->delete([
            $this->resolveKey(Key::OPENED),
            $this->resolveKey(Key::HALF_OPENED),
            $this->resolveKey(Key::FAILURES),
            $this->resolveKey(Key::SUCCESSES),
        ]);
    }
}
