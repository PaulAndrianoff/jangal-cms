<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'file_name', 'mime_type', 'size', 'path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}