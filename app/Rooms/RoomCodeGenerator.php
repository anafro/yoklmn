<?php

namespace App\Rooms;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class RoomCodeGenerator
{
    private Collection $alphabet;
    private int $length;

    public function __construct(string $alphabet, string $length)
    {
        $this->alphabet = collect(mb_str_split($alphabet));
        $this->length = $length;
    }

    public function generate(): string
    {
        $code_chars = collect();
        for ($i = 0; $i < $this->length; $i++) {
            $code_chars->push($this->alphabet->random());
        }
        return $code_chars->join('');
    }
}
