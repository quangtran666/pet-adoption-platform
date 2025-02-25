<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
