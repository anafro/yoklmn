<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;
use App\Models\User;
use App\Rooms\Room;

final class PlayingUserCannotJoinRoomsException extends YoklmnException
{
    public static function of(User $user, Room $room): self
    {
        return new self("User $user->name cannot join the room #$room->code,
                         because he is playing in the room #{$user->room()->code}.");
    }
}
