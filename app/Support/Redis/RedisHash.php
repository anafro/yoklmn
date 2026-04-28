<?php

namespace App\Support\Redis;

final class RedisHash extends RedisEntry
{
    public function has(string $key): bool
    {
        return redis('HEXISTS', [$this->key, $key]);
    }

    public function get(string $key): string
    {
        return redis('HGET', [$this->key, $key]);
    }

    public function put(string $key, string $value): void
    {
        redis('HSET', [$this->key, $key, $value]);
    }

    public function remove(string $key): void
    {
        redis('HDEL', [$this->key, $key]);
    }
}
