<?php

use App\Support\Text\Alphabet;
use Carbon\CarbonInterval;

return [
    'code' => [
        'regex' => '[а-яёА-ЯЁ]{4}',
        'alphabet' => [
            'latin'    => Alphabet::fromCharacters('ABVGDZIKLMNOPRSTUF'),
            'cyrillic' => Alphabet::fromCharacters('АБВГДЗИКЛМНОПРСТУФ'),
        ],
        'length' => 4,
        'attempts' => 64,
    ],
    'abandoned' => [
        'ttl' => CarbonInterval::minutes(3),
    ],
];
