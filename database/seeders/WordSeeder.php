<?php

namespace Database\Seeders;

use App\Models\Word;
use Override;

class WordSeeder extends TextFileSeeder
{
    public function __construct()
    {
        parent::__construct(__DIR__ . '/../../resources/data/russian-words/russian.txt', Word::class);
    }

    #[Override]
    public function mapToAttributes(string $line): array
    {
        return [
            'string' => mb_convert_encoding($line, 'utf-8', 'windows-1251'),
        ];
    }
}
