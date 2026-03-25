<?php

namespace App\Http\Controllers;

use App\Actions\RoomExists;
use App\Http\Requests\RoomExistsRequest;
use Illuminate\Http\JsonResponse;

class RoomExistsController extends Controller
{
    public function __invoke(RoomExistsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $code = $validated['code'];
        $exists = new RoomExists()->perform($code);

        return response()->json(compact('exists'));
    }
}
