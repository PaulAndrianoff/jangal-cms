<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'cover', 'media_file', 'status'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}