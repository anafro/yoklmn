<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

if (! function_exists('redis')) {
    /**
    * Executes a Redis command on a default connection.
    *
    * @param string $command A command keyword
    * @param array<mixed> | string | null $arguments A command arguments
    */
    function redis(string $command, array|string|null $arguments): mixed
    {
        $arguments = Arr::wrap($arguments);
        return Redis::connection()->client()->rawCommand($command, ...$arguments);
    }
}
