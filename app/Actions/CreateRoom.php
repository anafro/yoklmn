<?php

namespace App\Actions;

use App\Rooms\Exceptions\RoomCreateOutOfAttemptsException;
use App\Rooms\Room;
use App\Rooms\RoomCodeGenerator;
use App\Rooms\RoomType;
use Config;
use Illuminate\Support\Facades\Log;

final class CreateRoom
{
    public function perform(): Room
    {
        $attempt = 0;
        $attempts = Config::integer('rooms.code.attempts');
        $generator = app()->make(RoomCodeGenerator::class);

        while ($attempt++ < $attempts) {
            $code = $generator->generate();
            $room = Room::withCode($code);

            if ($room->doesntExist()) {
                $room->create(RoomType::VANILLA);
                return $room;
            }
        }

        throw RoomCreateOutOfAttemptsException::afterAttempts($attempts);
    }
}
