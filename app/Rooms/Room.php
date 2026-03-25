<?php

namespace App\Rooms;

use App\Rooms\Exceptions\RoomAlreadyExistsException;
use App\Rooms\Exceptions\RoomDoesntExistException;
use App\Support\Redis\BindsRedisEntries;
use App\Support\Redis\RedisBackedCase;

final class Room
{
    use BindsRedisEntries;

    private string $id;
    public RedisBackedCase $type;

    private function __construct(
        final public string $code,
    ) {
        $this->id = RoomRedisIds::room($code);
        $this->type = $this->enum('type', RoomType::class);
    }

    public static function withCode(string $code): self
    {
        return new self($code);
    }

    public function create(RoomType $type): void
    {
        $this->ensureDoesntExist();
        $this->type->setCase($type);
    }

    public function delete(): void
    {
        $this->ensureExists();
        $this->type->delete();
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
}
