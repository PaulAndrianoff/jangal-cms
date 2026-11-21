<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Ajoute cette ligne (ou vérifie qu'elle contient bien 'slug')
    protected $fillable = ['name', 'slug']; 

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}