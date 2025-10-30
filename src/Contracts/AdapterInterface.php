<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Contracts;

interface AdapterInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value, int $ttl): void;

    public function delete(string|array $keys): void;

    public function increment(string $key, int $ttl): int;
}
