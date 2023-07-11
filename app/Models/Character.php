<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'gender',
        'movie_id',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
