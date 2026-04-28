<?php

namespace App\Rooms;

use App\Support\Text\Alphabet;

final class RoomCodeTransliterator
{
    public function __construct(
        private Alphabet $cyrillicAlphabet,
        private Alphabet $latinAlphabet,
    ) {
        //
    }

    private function isCyrillic(string $code): bool
    {
        return $this->cyrillicAlphabet->accepts($code);
    }

    private function isLatin(string $code): bool
    {
        return $this->latinAlphabet->accepts($code);
    }

    public function latin(string $code): string
    {
        if ($this->isLatin($code)) {
            return $code;
        }

        return $this->cyrillicAlphabet->transliterate($code, $this->latinAlphabet);
    }

    public function cyrillic(string $code): string
    {
        if ($this->isCyrillic($code)) {
            return $code;
        }

        return $this->latinAlphabet->transliterate($code, $this->cyrillicAlphabet);
    }
}
