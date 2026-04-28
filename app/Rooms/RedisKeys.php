<?php

namespace App\Rooms;

final class RedisKeys
{
    private function __construct()
    {
        //
    }

    public static function forRoom(string $code): string
    {
        return 'room:' . $code;
    }

    public static function forNetwork(): string
    {
        return 'network';
    }
}
