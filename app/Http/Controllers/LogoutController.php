<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogoutRequest;
use Auth;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __invoke(LogoutRequest $request): JsonResponse
    {
        Auth::logout();

        session()->flush();
        session()->regenerate();
        session()->regenerateToken();

        return response()->json();
    }
}
