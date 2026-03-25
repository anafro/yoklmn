<?php

namespace App\Actions;

use App\Rooms\Room;

final class RoomExists
{
    public function perform(string $code): bool
    {
        $room = Room::withCode($code);
        return $room->exists();
    }
}
