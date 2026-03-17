<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserExistsRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserExistsController extends Controller
{
    public function __invoke(UserExistsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::whereName($validated['name'])->first();

        return response()->json([
            'exists' => $user !== null,
        ]);
    }
}
