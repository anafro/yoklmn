<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;

final class RoomCreateOutOfAttemptsException extends YoklmnException
{
    public static function afterAttempts(int $attempts): self
    {
        return new self("Room creation failed after $attempts attempts. Is room checking broken,
                         or the code format variations became too little?");
    }
}
