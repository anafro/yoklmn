<?php

namespace App\Support\Atomic;

use App\Support\Collections\Indexes;
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

    public static function from(string|array|Collection $string): string
    {
        if (is_string($string)) {
            return $string;
        }

        if (is_array($string)) {
            return join(array: $string);
        }

        return $string->join('');
    }

    public static function removeAt(string|array|Collection $string, int $index): string
    {
        if (! Indexes::inBounds($index, $string)) {
            return $string;
        }

        $characters = self::characters($string);
        unset($characters[$index]);
        return $characters->join('');
    }
}
