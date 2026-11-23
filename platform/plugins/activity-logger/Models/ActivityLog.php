<?php

namespace Platform\Plugins\ActivityLogger\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'module', 'description', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}