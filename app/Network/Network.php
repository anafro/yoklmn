<?php

namespace App\Network;

use App\Models\User;
use App\Network\Exceptions\PlayerAlreadyRegisteredInNetworkException;
use App\Network\Exceptions\PlayerNotRegisteredInNetworkException;
use App\Network\Exceptions\RoomAlreadyRegisteredInNetworkException;
use App\Network\Exceptions\RoomNotRegisteredInNetworkException;
use App\Rooms\Room;
use App\Support\Redis\BindsRedisEntries;
use App\Support\Redis\RedisEntryRegistrar;
use App\Support\Redis\RedisHash;
use App\Support\Redis\RedisSet;
use Illuminate\Support\Collection;

final class Network
{
    use BindsRedisEntries;

    private string $id;
    private RedisSet $roomCodes;
    private RedisHash $playerRoomCodes;

    public function __construct()
    {
        $this->id = 'network';
        $this->registrar = new RedisEntryRegistrar();
        $this->roomCodes = $this->set('rooms');
        $this->playerRoomCodes = $this->hash('player-rooms');
    }

    /**
     * @return Collection<int,Room>
     */
    public function rooms(): Collection
    {
        return $this->roomCodes
            ->collect()
            ->map(Room::withCode(...));
    }

    public function purge(): void
    {
        $this->rooms()->each(fn($r) => $r->delete());
        $this->roomCodes->delete();
        $this->playerRoomCodes->delete();
    }

    public function hasPlayerRegistered(User $player): bool
    {
        return $this->playerRoomCodes->has($player->name);
    }

    public function hasntPlayerRegistered(User $player): bool
    {
        return ! $this->hasPlayerRegistered($player);
    }

    public function registerPlayer(User $player, Room $room): void
    {
        if ($this->hasPlayerRegistered($player)) {
            throw PlayerAlreadyRegisteredInNetworkException::of(player: $player, room: $room, network: $this);
        }

        $this->playerRoomCodes->put($player->name, $room->code->latin);
    }

    public function unregisterPlayer(User $player): void
    {
        if ($this->hasntPlayerRegistered($player)) {
            throw PlayerNotRegisteredInNetworkException::of(player: $player, network: $this);
        }

        $this->playerRoomCodes->remove($player->name);
    }

    public function hasRoomRegistered(Room $room): bool
    {
        return $this->roomCodes->has($room->code->latin);
    }

    public function hasntRoomRegistered(Room $room): bool
    {
        return ! $this->hasRoomRegistered($room);
    }

    public function registerRoom(Room $room): void
    {
        if ($this->hasRoomRegistered($room)) {
            throw RoomAlreadyRegisteredInNetworkException::of(network: $this, room: $room);
        }

        $this->roomCodes->add($room->code->latin);
    }

    public function unregisterRoom(Room $room): void
    {
        if ($this->hasntRoomRegistered($room)) {
            throw RoomNotRegisteredInNetworkException::of(network: $this, room: $room);
        }

        $this->roomCodes->remove($room->code->latin);
    }

    public function getRoomOf(User $player): Room
    {
        if ($this->hasntPlayerRegistered($player)) {
            throw PlayerNotRegisteredInNetworkException::of(player: $player, network: $this);
        }

        $code = $this->playerRoomCodes->get($player->name);
        return room($code);
    }

    public function __toString(): string
    {
        return "[Global network]";
    }
}
