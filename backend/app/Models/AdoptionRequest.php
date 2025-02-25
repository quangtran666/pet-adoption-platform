<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $pet_id
 * @property int $user_id
 * @property string|null $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pet $pet
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\AdoptionRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdoptionRequest whereUserId($value)
 * @mixin \Eloquent
 */
class AdoptionRequest extends Model
{
    /** @use HasFactory<\Database\Factories\AdoptionRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id',
        'message',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function pet() : BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
