<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    /** @use HasFactory<\Database\Factories\PetFactory> */
    use HasFactory;

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
}
