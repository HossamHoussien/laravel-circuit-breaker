<?php

declare(strict_types=1);

namespace HossamHoussien\CircuitBreaker\Adapters;

use HossamHoussien\CircuitBreaker\Contracts\AdapterInterface;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class RedisAdapter implements AdapterInterface
{
    private Connection $redis;

    public function __construct(protected array $config)
    {
        $this->redis = $this->resolveConnection();
    }

    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    public function set(string $key, mixed $value, int $ttl): void
    {
        $this->redis->set($key, $value, 'EX', $ttl);
    }

    public function delete(string|array $keys): void
    {
        $keys = is_array($keys) ? $keys : [$keys];

        $this->redis->multi();

        foreach ($keys as $key) {
            $this->redis->del($key);
        }

        $this->redis->exec();
    }

    public function increment(string $key, int $ttl): int
    {
        $this->resetKeyIfInfiniteTTL($key);

        if (! $this->redis->get($key)) {
            $this->redis->multi();
            $this->redis->incr($key);
            $this->redis->expire($key, $ttl);

            return (int) ($this->redis->exec()[0] ?? 0);
        }

        return (int) $this->redis->incr($key);
    }

    protected function resolveConnection(): Connection
    {
        $connection = (string) ($this->config['connection'] ?? 'default');

        return Redis::connection($connection);
    }

    protected function resetKeyIfInfiniteTTL(string $key): void
    {
        if ($this->redis->ttl($key) === -1) {
            $this->redis->del($key);
        }
    }
}
