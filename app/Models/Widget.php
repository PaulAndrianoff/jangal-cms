<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = ['area', 'type', 'settings', 'order'];

    protected $casts = [
        'settings' => 'array',
    ];
}
