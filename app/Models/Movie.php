<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'is_published', 'poster_url'];
    public $timestamps = false;

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }
}
