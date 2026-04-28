<?php

namespace App\Network\Exceptions;

use App\Exceptions\YoklmnException;
use App\Models\User;
use App\Network\Network;

final class PlayerNotRegisteredInNetworkException extends YoklmnException
{
    public static function of(User $player, Network $network): self
    {
        return new self("Player $player is not registered in the network $network.");
    }
}
