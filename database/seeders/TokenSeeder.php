<?php

namespace Database\Seeders;

use App\Models\Token;
use Override;

class TokenSeeder extends TextFileSeeder
{
    public function __construct()
    {
        parent::__construct(__DIR__ . '/../../resources/data/russian-tokens/russian.txt', Token::class);
    }

    #[Override]
    public function mapToAttributes(string $line): array
    {
        return [
            'string' => $line,
        ];
    }
}
