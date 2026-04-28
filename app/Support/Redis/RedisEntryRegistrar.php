<?php

namespace App\Support\Redis;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;

final class RedisEntryRegistrar
{
    /** @var array<App\Support\Redis\RedisEntry> */
    private array $entries;

    public function __construct()
    {
        $this->entries = [];
    }

    /**
     * @param callable(App\Support\Redis\RedisEntry): void $consumer
     */
    public function each(callable $consumer): void
    {
        foreach ($this->entries as $entry) {
            $consumer($entry);
        }
    }

    /**
     * @template-covariance T as RedisEntry
     * @param T $entry
     * @return T
     */
    public function registered(RedisEntry $entry): mixed
    {
        $this->entries[] = $entry;
        return $entry;
    }

    public function delete(): void
    {
        $this->each(fn($entry) => $entry->delete());
    }

    public function expireIn(CarbonInterval $ttl): void
    {
        $this->each(fn($entry) => $entry->expireIn($ttl));
    }

    public function persist(): void
    {
        $this->each(fn($entry) => $entry->persist());
    }

    public function ttl(): ?CarbonInterval
    {
        if ($this->countEntries() === 0) {
            return null;
        }

        return $this->entries[0]->ttl();
    }

    public function syncExpiration(): void
    {
        $ttl = $this->ttl();

        if ($ttl === null) {
            $this->each(fn($entry) => $entry->persist());
        } else {
            $this->each(fn($entry) => $entry->expireIn($ttl));
        }
    }

    private function countEntries(): int
    {
        return count($this->entries);
    }
}
