<?php

namespace App\Rooms\Modes;

use App\Rooms\Room;

abstract class RoomMode
{
    protected function __construct(protected Room $room)
    {
        //
    }

    abstract public function accept(string $word): void;
}
