<?php

namespace App\Rooms;

use App\Exceptions\NotImplementedException;
use App\Models\User;
use App\Rooms\Exceptions\CannotJoinRunningRoomException;
use App\Rooms\Exceptions\CannotStartNotWaitingRoomException;
use App\Rooms\Exceptions\PlayingUserCannotJoinRoomsException;
use App\Rooms\Exceptions\RoomAlreadyExistsException;
use App\Rooms\Exceptions\RoomDoesntExistException;
use App\Support\Config\Configuration;
use App\Support\Redis\BindsRedisEntries;
use App\Support\Redis\RedisEnum;
use App\Support\Redis\RedisEntryRegistrar;
use App\Support\Redis\RedisInteger;
use App\Support\Redis\RedisOrderedSet;
use App\Support\Redis\RedisString;
use App\Support\Redis\RedisTimestamp;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final readonly class Room
{
    use BindsRedisEntries;

    private string $id;
    public RoomCode $code;
    private RedisEnum $type;
    private RedisEnum $status;
    private RedisString $host;
    private RedisInteger $round;
    private RedisString $token;
    private RedisTimestamp $timer;
    private RedisOrderedSet $players;

    private function __construct(
        string $code,
    ) {
        $this->code = RoomCode::fromString($code);
        $this->id = RedisKeys::forRoom($this->code->latin);
        $this->registrar = new RedisEntryRegistrar();
        $this->type = $this->enum('type', RoomType::class);
        $this->status = $this->enum('status', RoomStatus::class);
        $this->host = $this->string('host');
        $this->round = $this->integer('round');
        $this->token = $this->string('token');
        $this->timer = $this->timestamp('timer');
        $this->players = $this->orderedSet('players');
    }

    public static function withCode(string $code): self
    {
        return new self($code);
    }

    /**
     * @return EloquentCollection<int,User>
     */
    public function players(): EloquentCollection
    {
        return User::query()
            ->whereIn('name', $this->playerNames())
            ->get();
    }

    /**
     * @return Collection<int, string[]>
     */
    public function playerNames(): Collection
    {
        return $this->players->collect();
    }

    public function host(): User
    {
        return User::query()
            ->where('name', $this->host->get())
            ->first();
    }

    public function joinable(): bool
    {
        return $this->exists()
            && $this->status->get() === RoomStatus::RUNNING;
    }

    public function create(RoomType $type): void
    {
        $this->ensureDoesntExist();
        $this->type->set($type);
        network()->registerRoom($this);
    }

    public function add(User $player): void
    {
        $this->ensureExists();
        $status = $this->status->get();

        if ($player->isPlaying()) {
            throw PlayingUserCannotJoinRoomsException::of($player, $this);
        }

        if ($status === RoomStatus::RUNNING) {
            throw CannotJoinRunningRoomException::inRoom($this);
        }

        $this->players->add($player->name);
        network()->registerPlayer($player, $this);

        if ($status === RoomStatus::ABANDONED) {
            $this->host->set($player->name);
            $this->resurrect();
        }
    }

    public function remove(User $player): void
    {
        $this->ensureExists();
        $this->players->remove($player->name);
        network()->unregisterPlayer($player);

        if ($this->hostedBy($player)) {
            $this->host->set($this->players->pick());
        }

        if ($this->players->isEmpty()) {
            $this->abandon();
        }
    }

    public function start(): void
    {
        if ($this->status->get() !== RoomStatus::WAITING) {
            throw CannotStartNotWaitingRoomException::withRoom($this);
        }

        throw new NotImplementedException(__METHOD__);
    }

    public function accept(string $word): void
    {
        //
    }

    public function delete(): void
    {
        network()->unregisterRoom($this);
        $this->players()->each(fn($player) => network()->unregisterPlayer($player));
        $this->registrar->delete();
    }

    public function hostedBy(User $player): bool
    {
        return $this->host->get() === $player->name;
    }

    public function exists(): bool
    {
        return $this->type->exists();
    }

    public function doesntExist(): bool
    {
        return $this->type->doesntExist();
    }

    private function ensureExists(): void
    {
        if ($this->exists()) {
            return;
        }

        throw RoomDoesntExistException::withCode($this->code);
    }

    private function ensureDoesntExist(): void
    {
        if ($this->doesntExist()) {
            return;
        }

        throw RoomAlreadyExistsException::withCode($this->code);
    }

    private function abandon(): void
    {
        $this->status->set(RoomStatus::ABANDONED);
        $this->registrar->expireIn(Configuration::interval('rooms.abandoned.ttl'));
    }

    private function resurrect(): void
    {
        $this->status->set(RoomStatus::WAITING);
        $this->registrar->persist();
    }


    public function __toString(): string
    {
        return "$this->code";
    }
}
