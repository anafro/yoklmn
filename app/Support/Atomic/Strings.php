<?php

namespace App\Support\Atomic;

use Illuminate\Support\Collection;

final class Strings
{
    private function __construct()
    {
        //
    }

    /**
     * @param string|array<string>|Collection<int,string> $string
     * @return Collection<int,string>
     */
    public static function characters(string|array|Collection $string): Collection
    {
        if ($string instanceof Collection) {
            return $string;
        }

        if (is_array($string)) {
            return collect($string);
        }

        return collect(mb_str_split($string));
    }
}
