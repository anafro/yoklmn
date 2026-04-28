<?php

namespace App\Network\Exceptions;

use App\Exceptions\YoklmnException;
use App\Network\Network;
use App\Rooms\Room;

final class RoomAlreadyRegisteredInNetworkException extends YoklmnException
{
    private function __construct()
    {
        //
    }

    public static function of(Network $network, Room $room): self
    {
        return new self("A room $room is already registered in the network $network.");
    }
}
