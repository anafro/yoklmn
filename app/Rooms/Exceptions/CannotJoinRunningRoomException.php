<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;
use App\Rooms\Room;

final class CannotJoinRunningRoomException extends YoklmnException
{
    public static function inRoom(Room $room): self
    {
        return new self("Cannot join a running room #{$room->code}.");
    }
}
