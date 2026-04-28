<?php

namespace App\Support\Text\Exceptions;

use App\Exceptions\YoklmnException;
use App\Support\Text\Alphabet;

final class CharNotInAlphabetException extends YoklmnException
{
    public static function of(Alphabet $alphabet, string $character): self
    {
        return new self("Character $character is not in the alphabet $alphabet.");
    }
}
