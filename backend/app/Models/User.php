<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'description',
        'is_admin'
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

    public function pets() : HasMany
    {
        return $this->hasMany(Pet::class);
    }

    public function adoptionRequest() : HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function favorites() : HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function sentMessages() : HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages() : HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notifications() : HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
