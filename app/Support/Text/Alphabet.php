<?php

namespace App\Support\Text;

use AlphabetTransliterationException;
use App\Support\Atomic\Strings;
use App\Support\Collections\Indexes;
use App\Support\Text\Exceptions\AlphabetTransliterationException as AppAlphabetTransliterationException;
use App\Support\Text\Exceptions\CharNotInAlphabetException;
use Illuminate\Support\Collection;

final class Alphabet
{
    /**
     * @param Collection<int,string> $characters
     */
    private function __construct(
        private readonly Collection $characters,
    ) {
        //
    }

    /**
     * @param Collection<int,string>|array<string>|string $characters
     */
    public static function fromCharacters(Collection|array|string $characters): self
    {
        return new self(Strings::characters($characters));
    }

    public function has(string $character): bool
    {
        return $this->characters->contains($character);
    }

    public function accepts(string $string): bool
    {
        return Strings::characters($string)
                    ->every($this->has(...));
    }

    public function charWithCode(int $code): string
    {
        Indexes::ensureInBounds($code, $this->characters);
        return $this->characters[$code];
    }

    public function charCode(string $character): ?int
    {
        if ($this->characters->doesntContain($character)) {
            throw CharNotInAlphabetException::of($this, $character);
        }

        return $this->characters->search($character);
    }

    /**
     * @param string|array<string>|Collection<string,string> $alphabet
     */
    public function transliterate(string $string, Alphabet $target): string
    {
        return Strings::characters($string)
            ->map(fn($character) => self::transliterateChar($character, $target) ?? throw AlphabetTransliterationException::onTransliterating($character, from: $this, to: $target))
            ->join('');
    }

    /**
     * @param string|array<string>|Collection<string,string> $alphabet
     */
    private function transliterateChar(string $character, Alphabet $target): string
    {
        $charCode = $this->charCode($character);
        return $target->charWithCode($charCode);
    }

    public function __toString(): string
    {
        return $this->characters->join('');
    }
}
