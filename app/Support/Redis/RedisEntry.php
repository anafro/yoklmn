<?php

namespace App\Support\Redis;

use Carbon\CarbonInterval;

class RedisEntry
{
    public function __construct(
        protected string $key,
    ) {
        //
    }

    public function exists(): bool
    {
        return (bool) redis('EXISTS', $this->key);
    }

    public function doesntExist(): bool
    {
        return ! $this->exists();
    }

    public function delete(): void
    {
        redis('DEL', $this->key);
    }

    public function persist(): void
    {
        redis('PERSIST', $this->key);
    }

    public function expireIn(CarbonInterval $ttl): void
    {
        redis('PEXPIRE', [$this->key, (int) $ttl->totalMilliseconds]);
    }

    public function ttl(): ?CarbonInterval
    {
        $pttl = $this->pttl();

        if ($pttl <= 0) {
            return null;
        }

        return CarbonInterval::milliseconds($pttl);
    }

    private function pttl(): int
    {
        return (int) redis('PTTL', $this->key);
    }
}
