<?php

namespace App\Support\Redis;

final class RedisInteger extends RedisEntry
{
    public function __construct(string $key)
    {
        parent::__construct($key);
    }

    public function get(): int
    {
        return (int) redis('GET', $this->key);
    }

    public function set(int $integer): void
    {
        redis('SET', [$this->key, $integer]);
    }

    public function incr(): void
    {
        redis('INCR', $this->key);
    }

    public function decr(): void
    {
        redis('DECR', $this->key);
    }

    public function plus(int $amount): void
    {
        redis('INCRBY', [$this->key, $amount]);
    }

    public function minus(int $amount): void
    {
        redis('DECRBY', [$this->key, $amount]);
    }
}
