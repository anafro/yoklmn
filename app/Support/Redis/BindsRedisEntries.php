<?php

namespace App\Support\Redis;

trait BindsRedisEntries
{
    protected function string(string $name): RedisString
    {
        return new RedisString($this->entryKey($name));
    }
    /**
     * @template T of \BackedEnum
     * @param string $name
     * @param class-string<T> $enum
     * @return RedisBackedCase<T>
     */
    protected function enum(string $name, string $enum): RedisBackedCase
    {
        return new RedisBackedCase($this->entryKey($name), $enum);
    }

    private function entryKey(string $name): string
    {
        return $this->id . ':' . $name;
    }
}
