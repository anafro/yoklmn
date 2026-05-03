<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    public static function exists(string $word): bool
    {
        return self::query()->where('string', $word)->exists();
    }

    public static function doesntExist(string $word): bool
    {
        return ! self::exists($word);
    }
}
