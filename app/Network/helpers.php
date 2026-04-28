<?php

use App\Network\Network;

if (! function_exists('network')) {
    function network(): Network
    {
        return resolve(Network::class);
    }
}
