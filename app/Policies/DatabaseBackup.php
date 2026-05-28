<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DatabaseBackup
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ViewAny:DatabaseBackup');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('Create:DatabaseBackup');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('Delete:DatabaseBackup');
    }
}
