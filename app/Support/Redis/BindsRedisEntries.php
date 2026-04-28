<?php

namespace App\Support\Redis;

trait BindsRedisEntries
{
    protected readonly RedisEntryRegistrar $registrar;

    protected function string(string $name): RedisString
    {
        return $this->registrar->registered(new RedisString($this->entryKey($name)));
    }

    protected function integer(string $name): RedisInteger
    {
        return $this->registrar->registered(new RedisInteger($this->entryKey($name)));
    }

    protected function timestamp(string $name): RedisTimestamp
    {
        return $this->registrar->registered(new RedisTimestamp($this->entryKey($name)));
    }

    /**
     * @template T of \BackedEnum
     * @param string $name
     * @param class-string<T> $enum
     * @return RedisEnum<T>
     */
    protected function enum(string $name, string $enum): RedisEnum
    {
        return $this->registrar->registered(new RedisEnum($this->entryKey($name), $enum));
    }

    protected function orderedSet(string $name): RedisOrderedSet
    {
        return $this->registrar->registered(new RedisOrderedSet($this->entryKey($name)));
    }

    protected function set(string $name): RedisSet
    {
        return $this->registrar->registered(new RedisSet($this->entryKey($name)));
    }

    protected function hash(string $name): RedisHash
    {
        return $this->registrar->registered(new RedisHash($this->entryKey($name)));
    }

    private function entryKey(string $name): string
    {
        return $this->id . ':' . $name;
    }
}
