<?php

namespace App\Support\Redis;

class RedisString extends RedisEntry
{
    public function __construct(
        protected string $key,
    ) {
        parent::__construct($key);
    }

    public function get(): string
    {
        return redis('GET', $this->key);
    }

    public function set(string $value): void
    {
        redis('SET', [$this->key, $value]);
    }

    public function length(): int
    {
        return mb_strlen($this->get());
    }
}
