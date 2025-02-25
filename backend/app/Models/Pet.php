<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending_approval';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ADOPTED = 'adopted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'name',
        'age',
        'type',
        'description',
        'location',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images() : HasMany
    {
        return $this->hasMany(PetImage::class);
    }

    public function adoptionRequests() : HasMany
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    public function favorites() : HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function scopeActive(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAdopted(Builder $query) : Builder
    {
        return $query->where('status', self::STATUS_ADOPTED);
    }

    public function scopeOfType(Builder $query, string $type) : Builder
    {
        return $query->where('type', $type);
    }

    public function scopeInLocation(Builder $query, string $location) : Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeSearch(Builder $query, string $searchTerm) : Builder
    {
        return $query->where(function ($query) use($searchTerm) {
            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }
}
