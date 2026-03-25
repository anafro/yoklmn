<?php

namespace App\Rooms\Exceptions;

use App\Exceptions\YoklmnException;

final class RoomDoesntExistException extends YoklmnException
{
    public static function withCode(string $code): self
    {
        return new self("The room with code $code doesn't exist.
                         Did you forget to create it first?");
    }
}
