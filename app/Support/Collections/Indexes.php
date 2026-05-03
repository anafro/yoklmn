<?php

namespace App\Support\Collections;

use App\Support\Collections\Exceptions\IndexOutOfBoundsException;
use Countable;

final class Indexes
{
    private function __construct()
    {
        //
    }
    /**
     * @param Countable|mixed[] $items
     */
    public static function ensureInBounds(int $index, Countable|array $items): void
    {
        if (self::inBounds($index, $items)) {
            return;
        }

        throw IndexOutOfBoundsException::of($index, $items);
    }
    /**
     * @param Countable|mixed[]|string $items
     */
    public static function inBounds(int $index, Countable|array|string $items): bool
    {
        $count = is_string($items) ? 'mb_strlen' : 'count';

        if ($count($items) === 0) {
            return false;
        }

        return 0 <= $index && $index < $count($items);
    }
}
