<?php

use App\Rooms\Room;

if (! function_exists('room')) {
    function room(string $code): Room
    {
        return Room::withCode($code);
    }
}
