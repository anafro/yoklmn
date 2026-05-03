<?php

namespace App\Http\Controllers;

use App\Http\Requests\RandomTokenRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Token;

class RandomTokenController extends Controller
{
    public function __invoke(RandomTokenRequest $request): JsonResponse
    {
        $request->validated();
        return response()->json([
            'token' => Token::random(),
        ]);
    }
}
