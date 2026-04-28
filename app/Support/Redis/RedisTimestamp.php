<?php

namespace App\Support\Redis;

use Carbon\Carbon;

final class RedisTimestamp extends RedisEntry
{
    public function get(): Carbon
    {
        $timestamp = (int) redis('GET', $this->key);
        return Carbon::createFromTimestamp($timestamp);
    }

    public function set(Carbon $carbon): void
    {
        $timestamp = $carbon->getTimestampMs();
        redis('SET', [$this->key, $timestamp]);
    }
}
