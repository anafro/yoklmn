<?php

namespace App\Broadcasting;

use Anafro\Biosphere\Channels\Channel;
use Anafro\Biosphere\Messages\Message;
use App\Rooms\Room;
use Illuminate\Support\Facades\Log;

class RoomChannel extends Channel
{
    public function __construct(string $pattern, string $name)
    {
        parent::__construct($pattern, $name);
    }

    /**
     * @param \App\Models\User $player
     */
    public function authorize(mixed $player): bool
    {
        $code = $this->parameter('code');
        $room = room($code);

        return $room->exists()
            && $room->joinable()
            && $player->isNotPlaying();
    }

    /**
     * @param \App\Models\User $player
     */
    public function connected(mixed $player): void
    {
        $code = $this->parameter('code');
        $room = room($code);

        $room->add($player);
        $this->send('join', [
            'player' => $player->name,
            ...$this->stateOf($room),
        ]);
    }

    /**
     * @param \App\Models\User $player
     */
    public function disconnected(mixed $player): void
    {
        $code = $this->parameter('code');
        $room = room($code);

        $room->remove($player);
        $this->send('quit', [
            'player' => $player->name,
            ...$this->stateOf($room),
        ]);
    }

    public function message(Message $message): void
    {
        $code = $this->parameter('code');
        $room = room($code);
        $room->status()->message($room, $this, $message);
    }

    /**
     * @param \App\Models\User $player
     */
    public function heartbeat(mixed $player): void
    {
        //
    }

    private function stateOf(Room $room): array
    {
        return [
            "players" => $room->playerNames(),
            "host" => $room->hostName(),
        ];
    }
}
