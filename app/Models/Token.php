<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public static function random(): string
    {
        return self::query()->inRandomOrder()->first()->string;
    }
}
