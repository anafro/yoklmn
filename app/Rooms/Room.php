<?php

namespace App\Rooms;

use Anafro\Biosphere\Channels\Channel;
use Anafro\Biosphere\Channels\ChannelRegistrar;
use App\Models\Token;
use App\Models\User;
use App\Models\Word;
use App\Rooms\Exceptions\CannotJoinRunningRoomException;
use App\Rooms\Exceptions\PlayingUserCannotJoinRoomsException;
use App\Rooms\Exceptions\RoomAlreadyExistsException;
use App\Rooms\Exceptions\RoomDoesntExistException;
use App\Support\Atomic\Strings;
use App\Support\Config\Configuration;
use App\Support\Redis\BindsRedisEntries;
use App\Support\Redis\RedisEnum;
use App\Support\Redis\RedisEntryRegistrar;
use App\Support\Redis\RedisInteger;
use App\Support\Redis\RedisOrderedSet;
use App\Support\Redis\RedisSet;
use App\Support\Redis\RedisString;
use App\Support\Redis\RedisTimestamp;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final readonly class Room
{
    use BindsRedisEntries;

    private string $id;
    public RoomCode $code;
    public Channel $channel;
    private RedisEnum $type;
    private RedisEnum $status;
    private RedisString $host;
    private RedisString $turn;
    private RedisString $token;
    private RedisString $input;
    private RedisInteger $caret;
    private RedisOrderedSet $players;
    private RedisInteger $time;
    private RedisString $timeoutSchedule;
    private RedisInteger $round;
    private RedisSet $words;

    private function __construct(
        string $code,
    ) {
        $this->code = RoomCode::fromString($code);
        $this->id = RedisKeys::forRoom($this->code->latin);
        $this->channel = resolve(ChannelRegistrar::class)->find("Room #{$this->code->latin}");
        $this->registrar = new RedisEntryRegistrar();
        $this->type = $this->enum('type', RoomType::class);
        $this->status = $this->enum('status', RoomStatus::class);
        $this->host = $this->string('host');
        $this->turn = $this->string('turn');
        $this->token = $this->string('token');
        $this->input = $this->string('input');
        $this->caret = $this->integer('caret');
        $this->players = $this->orderedSet('players');
        $this->time = $this->integer('time');
        $this->timeoutSchedule = $this->string('timeout-schedule');
        $this->round = $this->integer('round');
        $this->words = $this->set('words');
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

    public function playerCount(): int
    {
        return $this->players->count();
    }

    public function host(): User
    {
        return User::query()
            ->where('name', $this->hostName())
            ->first();
    }

    public function hostName(): string
    {
        return $this->host->get();
    }

    public function status(): RoomStatus
    {
        return $this->status->get();
    }

    public function token(): string
    {
        return $this->token->get();
    }

    public function turn(): string
    {
        return $this->turn->get();
    }

    public function isTurnOf(User $player)
    {
        return $this->turn() === $player->name;
    }

    public function input(): string
    {
        return $this->input->get();
    }

    public function caret(): int
    {
        return $this->caret->get();
    }

    public function time(): int
    {
        return $this->time->get();
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
        $this->status->set(RoomStatus::ABANDONED);
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

    public function start()
    {
        if ($this->playerCount() < 2) {
            return $this->sendServerMessage('[!] Для игры нужно хотя бы два игрока!');
        }

        if ($this->status() === RoomStatus::RUNNING) {
            return $this->sendServerMessage('[!] Игра уже запущена!');
        }

        $this->status->set(RoomStatus::RUNNING);
        $this->turn->set($this->players->pick());
        $this->nextToken();
        $this->clearInput();
        $this->sendServerMessage('[Начинаем] Всем хорошей игры и удачи!');
        $this->resetTimeout();
        $this->channel->send('start', [
            ...$this->turnState()
        ]);
    }

    public function abort(): void
    {
        //
    }

    public function submit(string $word)
    {
        if ($this->hasWord($word)) {
            return $this->channel->send('check', [
                'type' => 'used',
            ]);
        }

        if (! str_contains(mb_strtolower($word), strtolower($this->token()))) {
            return $this->channel->send('check', [
                'type' => 'bad',
            ]);
        }

        if (Word::doesntExist($word)) {
            return $this->channel->send('check', [
                'type' => 'bad',
            ]);
        }

        $this->rememberWord($word);
        $this->next();

        $this->channel->send('check', [
            'type' => 'ok',
            ...$this->turnState(),
        ]);
    }

    public function next(): void
    {
        $this->nextPlayer();
        $this->nextToken();
        $this->clearInput();
        $this->resetTimeout();
    }

    public function hasWord(string $word): bool
    {
        return $this->words->has($word);
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

    public function sendServerMessage(string $text)
    {
        $this->channel->send('chat', compact('text'));
    }

    public function sendPlayerMessage(User $player, string $text)
    {
        $this->channel->send('chat', [
            ...compact('text'),
            'player' => $player->name,
        ]);
    }

    public function exists(): bool
    {
        return $this->type->exists();
    }

    public function doesntExist(): bool
    {
        return $this->type->doesntExist();
    }

    public function write(string $char): void
    {
        $caret = $this->caret();
        $input = $this->input();

        $this->caret->incr();
        $newInput = mb_substr($input, 0, length: $caret) . $char . mb_substr($input, $caret);
        $this->input->set($newInput);
        $this->channel->send('write', [
            ...compact('char'),
            ...$this->inputState(),
        ]);
    }

    public function erase(bool $fast): void
    {
        $caret = $this->caret();
        $input = $this->input();
        $newInput = $fast
            ? mb_substr($input, $caret)
            : Strings::removeAt($input, $caret - 1);
        $newCaret = $fast
            ? 0
            : max(0, $caret - 1);

        $this->input->set($newInput);
        $this->caret->set($newCaret);
        $this->channel->send('erase', [
            ...compact('fast'),
            ...$this->inputState(),
        ]);
    }

    public function move(bool $fast, string $direction): void
    {
        $caret = $this->caret();
        $this->caret->set(match (true) {
            $fast && $direction === 'right'   => $this->input->length(),
            $fast && $direction === 'left'    => 0,
            ! $fast && $direction === 'right' => min($this->input->length(), $caret + 1),
            ! $fast && $direction === 'left'  => max(0, $caret - 1),
        });
        $this->channel->send('move', [
            ...compact('fast', 'direction'),
            ...$this->inputState(),
        ]);
    }

    public function timeout(): void
    {
        $this->next();
        $this->channel->send('timeout', [
            ...$this->inputState(),
            ...$this->turnState(),
        ]);
        // TODO <<<<<<<<<<<<<<<<
    }

    public function resetTimeout(): void
    {
        if ($this->timeoutSchedule->exists()) {
            $currentTimeoutSchedule = $this->timeoutSchedule->get();
            $this->channel->cancel($currentTimeoutSchedule);
        }

        $newTimeoutSchedule = Str::uuid();
        $newTimeout = max(4000, 10000 - $this->round->get() * 200);
        $this->time->set($newTimeout);
        $this->channel->schedule($newTimeoutSchedule, "timeout", $newTimeout, []);
        $this->timeoutSchedule->set($newTimeoutSchedule);
    }

    //--

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

    private function nextPlayer()
    {
        $turn = $this->turn();
        $index = $this->players->indexOf($turn);
        $newIndex = ($index + 1) % $this->playerCount();
        $newTurn = $this->players->at($newIndex);
        $this->turn->set($newTurn);

        if ($newIndex === 0) {
            $this->round->incr();
        }
    }

    private function nextToken(): void
    {
        $this->token->set(Token::random());
    }

    private function clearInput(): void
    {
        $this->caret->set(0);
        $this->input->set('');
    }

    private function rememberWord(string $word): void
    {
        $this->words->add($word);
    }

    private function inputState(): array
    {
        return [
            'caret' => $this->caret(),
            'input' => $this->input(),
        ];
    }

    private function turnState(): array
    {
        return [
            'turn' => $this->turn(),
            'token' => $this->token(),
            'time' => $this->time(),
        ];
    }

    public function __toString(): string
    {
        return "$this->code";
    }
}
