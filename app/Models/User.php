<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Network\Facades\Network;
use App\Rooms\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function room(): ?Room
    {
        if (network()->hasntPlayerRegistered($this)) {
            return null;
        }

        return network()->getRoomOf($this);
    }

    public function isPlaying(): bool
    {
        if (network()->hasPlayerRegistered($this)) {
            $room = $this->room();
            return $room->exists();
        }

        return false;
    }

    public function isNotPlaying(): bool
    {
        return ! $this->isPlaying();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
