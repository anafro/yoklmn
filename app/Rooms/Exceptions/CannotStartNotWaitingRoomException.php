<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;
use App\Rooms\Room;

final class CannotStartNotWaitingRoomException extends YoklmnException
{
    public static function withRoom(Room $room): self
    {
        return new self("Cannot start a game in a {$room->status->get()} room {$room->code}.");
    }
}
