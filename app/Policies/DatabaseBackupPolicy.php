<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DatabaseBackupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ViewAny:DatabaseBackup');
    }

    public function create(User $user): bool
    {
        return auth()->check() && $user->hasPermission('Create:DatabaseBackup');
    }

    public function delete(User $user): bool
    {
        return auth()->check() && $user->hasPermission('Delete:DatabaseBackup');
    }
}
