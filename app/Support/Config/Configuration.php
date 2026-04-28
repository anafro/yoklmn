<?php

namespace App\Support\Config;

use App\Support\Text\Alphabet;
use Carbon\CarbonInterval;
use Config;

final class Configuration extends Config
{
    private function __construct()
    {
        //
    }

    public static function interval(string $key, ?CarbonInterval $default = null): CarbonInterval
    {
        return self::get($key, $default);
    }

    public static function alphabet(string $key, ?Alphabet $default = null): Alphabet
    {
        return self::get($key, $default);
    }
}
