<?php

namespace App\Http\Controllers;

use App\Rooms\Room;
use App\Rooms\RoomCodeTransliterator;
use Inertia\Inertia;

class RoomPageController extends Controller
{
    public function __invoke(string $code): \Inertia\Response
    {
        $transliterator = resolve(RoomCodeTransliterator::class);
        $room = Room::withCode($code);
        return Inertia::render($room->exists() ? 'Mode/Room' : 'Mode/RoomNotFound', [
            'code' => $room->code,
        ]);
    }
}
