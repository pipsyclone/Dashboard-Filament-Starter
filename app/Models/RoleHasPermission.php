<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsTo(Permissions::class, 'permission_id');
    }
}
