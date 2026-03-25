<?php

namespace App\Rooms;

final class RoomRedisIds
{
    private function __construct()
    {
        //
    }

    public static function room(string $code): string
    {
        return 'room:' . $code;
    }
}
