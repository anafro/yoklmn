<?php

namespace App\Support\Collections\Exceptions;

use App\Exceptions\YoklmnException;
use Countable;

final class IndexOutOfBoundsException extends YoklmnException
{
    public static function of(int $index, Countable $items): self
    {
        return new self("Index $index is out of bounds of items with a length of " . count($items));
    }
}
