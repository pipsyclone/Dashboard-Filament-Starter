<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLogs extends Model
{
    use SoftDeletes;
    
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'user_agent',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
