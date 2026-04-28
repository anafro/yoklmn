<?php

namespace App\Support\Redis;

use Illuminate\Support\Collection;

final class RedisSet extends RedisEntry
{
    public function has(string $item): bool
    {
        return redis('SISMEMBER', [$this->key, $item]);
    }

    public function add(string $item): void
    {
        redis('SADD', [$this->key, $item]);
    }

    public function remove(string $item): void
    {
        redis('SREM', [$this->key, $item]);
    }

    /**
     * @return Collection<int,string>
     */
    public function collect(): Collection
    {
        if ($this->doesntExist()) {
            return collect([]);
        }

        return collect(redis('SMEMBERS', $this->key));
    }
}
