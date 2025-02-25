<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $age
 * @property string $type
 * @property string $description
 * @property string $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdoptionRequest> $adoptionRequests
 * @property-read int|null $adoption_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PetImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Pet active()
 * @method static Builder<static>|Pet adopted()
 * @method static \Database\Factories\PetFactory factory($count = null, $state = [])
 * @method static Builder<static>|Pet inLocation(string $location)
 * @method static Builder<static>|Pet newModelQuery()
 * @method static Builder<static>|Pet newQuery()
 * @method static Builder<static>|Pet ofType(string $type)
 * @method static Builder<static>|Pet pending()
 * @method static Builder<static>|Pet query()
 * @method static Builder<static>|Pet search(string $searchTerm)
 * @method static Builder<static>|Pet whereAge($value)
 * @method static Builder<static>|Pet whereCreatedAt($value)
 * @method static Builder<static>|Pet whereDescription($value)
 * @method static Builder<static>|Pet whereId($value)
 * @method static Builder<static>|Pet whereLocation($value)
 * @method static Builder<static>|Pet whereName($value)
 * @method static Builder<static>|Pet whereStatus($value)
 * @method static Builder<static>|Pet whereType($value)
 * @method static Builder<static>|Pet whereUpdatedAt($value)
 * @method static Builder<static>|Pet whereUserId($value)
 * @mixin \Eloquent
 */
class Pet extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending_approval';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ADOPTED = 'adopted';

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
