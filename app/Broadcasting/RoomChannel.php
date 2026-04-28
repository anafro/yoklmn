<?php

namespace App\Broadcasting;

use Anafro\Biosphere\Channels\Channel;
use Anafro\Biosphere\Messages\Message;
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
            'players' => $room->playerNames(),
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
            'players' => $room->playerNames(),
        ]);
    }

    public function message(Message $message): void
    {
        match ($message->event) {
            "ping"  => $this->send('pong', []),
            "chat"  => $this->send('chat', [
                "text" => $message->data['text'],
                "player" => $message->user->name,
            ]),

            default => null,
        };

        $this->send('$event', $message->data);

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }


    /**
     * @param \App\Models\User $player
     */
    public function heartbeat(mixed $player): void
    {
        //
    }
}
