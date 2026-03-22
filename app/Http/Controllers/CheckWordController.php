<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckWordRequest;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CheckWordController extends Controller
{
    public function __invoke(CheckWordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $word = Str::of($request['word'])->lower();
        $token = Str::of($request['token'])->lower();

        if (! $word->contains($token)) {
            return response()->json([
                'correct' => false,
            ]);
        }

        return response()->json([
            'correct' => Word::query()->where('string', $word)->exists(),
        ]);
    }
}
