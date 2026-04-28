<?php

use App\Broadcasting\RoomChannel;
use App\Models\User;
use Anafro\Biosphere\Facades\Biosphere;

Biosphere::channel("/Room #(?<code>.*)/", RoomChannel::class);
