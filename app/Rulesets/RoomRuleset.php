<?php

namespace App\Rulesets;

use Config;

final class RoomRuleset
{
    private function __construct()
    {
        //
    }
    /**
     * @return array<string,array<int,string>>
     */
    public static function code(): array
    {
        return [
            'code' => [
                'required',
                'size:' . Config::integer('rooms.code.length'),
            ],
        ];
    }
}
