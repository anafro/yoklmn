<?php

namespace App\Rooms;

enum RoomType: string
{
    case VANILLA = 'vanilla';
    case BINGO = 'bingo';
    case MINES = 'mines';
    case TRIPPLE = 'tripples';
    case SPELLS = 'spells';
    case BOMB = 'bomb';
}
