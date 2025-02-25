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
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pet $pet
 * @method static \Database\Factories\PetImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PetImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PetImage extends Model
{
    /** @use HasFactory<\Database\Factories\PetImageFactory> */
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'path'
    ];

    public function pet() : BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
