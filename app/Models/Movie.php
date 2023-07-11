<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'episode_id', 'opening_crawl', 'director', 'producer', 'release_date', 'url'];

    public function planets(): HasMany
    {
        return $this->hasMany(Planet::class, 'movie_id', 'episode_id');
    }

    public function spacies(): HasMany
    {
        return $this->hasMany(Specie::class, 'movie_id', 'episode_id');
    }

    public function starships(): HasMany
    {
        return $this->hasMany(Starship::class, 'movie_id', 'episode_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'movie_id', 'episode_id');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'movie_id', 'episode_id');
    }
}
