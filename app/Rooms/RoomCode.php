<?php

namespace App\Rooms;

final readonly class RoomCode
{
    private function __construct(
        public string $cyrillic,
        public string $latin,
    ) {
        //
    }

    public static function fromString(string $code): self
    {
        $transliterator = resolve(RoomCodeTransliterator::class);
        $cyrillic = $transliterator->cyrillic($code);
        $latin = $transliterator->latin($code);

        return new self($cyrillic, $latin);
    }

    public static function same(RoomCode $a, RoomCode $b): bool
    {
        return $a->cyrillic === $b->cyrillic && $a->latin === $b->latin;
    }

    public function __toString(): string
    {
        return "#$this->cyrillic/id=$this->latin";
    }
}
