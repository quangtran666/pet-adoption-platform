<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
