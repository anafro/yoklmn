<?php

namespace App\Http\Controllers;

use App\Rooms\Room;
use Inertia\Inertia;

class RoomPageController extends Controller
{
    public function __invoke(string $code): \Inertia\Response
    {
        $room = Room::withCode($code);
        return Inertia::render($room->exists() ? 'Mode/Room' : 'Mode/RoomNotFound', compact('code'));
    }
}
