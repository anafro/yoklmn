<?php

namespace App\Support\Redis;

use Illuminate\Support\Collection;

final class RedisOrderedSet extends RedisEntry
{
    public function __construct(
        protected string $key,
    ) {
        parent::__construct($key);
    }

    public function has(string $item): bool
    {
        return redis('ZSCORE', [$this->key, $item]) !== null;
    }

    public function add(string $item): void
    {
        redis('ZADD', [$this->key, now()->getTimestampMs(), $item]);
    }

    public function remove(string $item): void
    {
        redis('ZREM', [$this->key, $item]);
    }

    /**
     * @return Collection<int,string>
     */
    public function collect(): Collection
    {
        if ($this->doesntExist()) {
            return collect([]);
        }

        return collect(redis('ZRANGE', [$this->key, '0', '-1']));
    }

    public function pick(): string
    {
        return redis('ZRANDMEMBER', $this->key);
    }

    public function count(): int
    {
        return (int) redis('ZCARD', $this->key);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
