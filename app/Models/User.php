<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firebase_uid',
        'is_admin',
        'last_login_at',
        'banned_at',
        'suspended_until',
        'ban_reason',
        'locale',
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
            'last_login_at'     => 'datetime',
            'banned_at'         => 'datetime',
            'suspended_until'   => 'datetime',
            'is_admin'          => 'boolean',
            'password'          => 'hashed',
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function isSuspended(): bool
    {
        return $this->suspended_until !== null && $this->suspended_until->isFuture();
    }

    public function isActive(): bool
    {
        return ! $this->isBanned() && ! $this->isSuspended();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
