<?php

namespace App\Http\Controllers;

use App\Actions\CreateRoom;
use App\Http\Requests\CreateRoomRequest;
use Illuminate\Http\JsonResponse;

class CreateRoomController extends Controller
{
    public function __invoke(CreateRoomRequest $request): JsonResponse
    {
        $validated = $request->validated();  // Empty request
        $room = new CreateRoom()->perform();

        return response()->json([
            'code' => $room->code,
        ]);
    }
}
