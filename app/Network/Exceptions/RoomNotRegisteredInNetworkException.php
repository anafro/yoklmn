<?php

namespace App\Network\Exceptions;

use App\Exceptions\YoklmnException;
use App\Network\Network;
use App\Rooms\Room;

final class RoomNotRegisteredInNetworkException extends YoklmnException
{
    public static function of(Room $room, Network $network): self
    {
        return new self("The room $room not found in the network $network.");
    }
}
