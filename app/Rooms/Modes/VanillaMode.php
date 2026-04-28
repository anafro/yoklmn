<?php

namespace App\Rooms\Modes;

use App\Support\Redis\BindsRedisEntries;
use App\Support\Redis\RedisSet;

final class VanillaMode extends RoomMode
{
    use BindsRedisEntries;

    private RedisSet $used;

    public function __construct()
    {
        $this->used = $this->set('used');
    }

    public function accept(string $word): void
    {
        if ($this->used->has($word)) {
            //
        }
    }
}
