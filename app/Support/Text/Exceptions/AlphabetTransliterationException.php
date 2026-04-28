<?php

namespace App\Support\Text\Exceptions;

use App\Exceptions\YoklmnException;
use App\Support\Text\Alphabet;

final class AlphabetTransliterationException extends YoklmnException
{
    public static function onTransliterating(string $string, Alphabet $from, Alphabet $to): self
    {
        return new self("'$string' cannot be transliterated from alphabet '$from' to '$to'.
                          Does it have unrecognized chars?");
    }
}
