<?php

namespace App\Network\Exceptions;

use App\Exceptions\YoklmnException;
use App\Models\User;
use App\Network\Network;
use App\Rooms\Room;

final class PlayerAlreadyRegisteredInNetworkException extends YoklmnException
{
    private function __construct()
    {
        //
    }

    public static function of(User $player, Room $room, Network $network): self
    {
        return new self("The player $player is already registered in the network $network in room $room");
    }
}
