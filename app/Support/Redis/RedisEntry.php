<?php

namespace App\Support\Redis;

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
        redis('DELETE', $this->key);
    }
}
