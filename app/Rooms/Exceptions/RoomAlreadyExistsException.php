<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;

final class RoomAlreadyExistsException extends YoklmnException
{
    public static function withCode(string $code): self
    {
        return new self("Room with code $code already exists.
                         Did you forget to check if it doesn't?");
    }
}
