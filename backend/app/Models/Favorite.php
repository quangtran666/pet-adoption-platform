<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    /** @use HasFactory<\Database\Factories\FavoriteFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pet() : BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}
