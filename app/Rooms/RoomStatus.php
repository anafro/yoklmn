<?php

namespace App\Rooms;

enum RoomStatus: string
{
    case WAITING = 'waiting';
    case RUNNING = 'running';
    case ABANDONED = 'abandoned';
}
