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
}
