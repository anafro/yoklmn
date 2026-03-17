<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $remember = true;

        if (Auth::attempt($validated, $remember)) {
            session()->regenerate();
            return response()->json();
        }

        return response()->json([
            'message' => __('auth.password'),
        ], Response::HTTP_UNAUTHORIZED);
    }
}
