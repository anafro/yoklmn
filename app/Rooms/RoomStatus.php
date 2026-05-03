<?php

namespace App\Rooms;

use Anafro\Biosphere\Channels\Channel;
use Anafro\Biosphere\Messages\Message;

enum RoomStatus: string
{
    case WAITING = 'waiting';
    case RUNNING = 'running';
    case ABANDONED = 'abandoned';

    public function message(Room $room, Channel $channel, Message $message): void
    {
        $status = $this->name;
        $event = $message->event;

        if ($event === 'chat') {
            $room->sendPlayerMessage($message->user, $message->data['text']);
            return;
        }

        self::{$status}($room, $channel, $message);
    }

    private static function abandoned(Room $room, Channel $channel, Message $message): void
    {
        //
    }

    private static function waiting(Room $room, Channel $channel, Message $message)
    {
        $event = $message->event;

        switch ($event) {
            case 'start':
                if ($room->playerCount() < 2) {
                    $room->sendServerMessage('Для старта нужно хотя бы два игрока');
                    return;
                }

                if (! $room->hostedBy($message->user)) {
                    return $room->sendServerMessage('Ты не можешь запустить игру!');
                }

                $room->start();
                break;
        }
    }

    private static function running(Room $room, Channel $channel, Message $message): void
    {
        $event = $message->event;
        $wordInputEvents = ['write', 'move', 'erase', 'submit'];

        if (
            array_search($event, $wordInputEvents) &&
            ! $room->isTurnOf($message->user)
        ) {
            return;
        }

        match ($event) {
            'write'   => $room->write(char: $message->data['char']),
            'move'    => $room->move(fast: $message->data['fast'], direction: $message->data['direction']),
            'erase'   => $room->erase(fast: $message->data['fast']),
            'submit'  => $room->submit($message->data['word']),
            'timeout' => $room->timeout(),
            default   => null,
        };
    }
}
