<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('ViewAny:Role');
    }

    public function view(User $user, $model): bool
    {
        return $user->hasPermission('View:Role');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('Create:Role');
    }

    public function update(User $user, $model): bool
    {
        return $user->hasPermission('Update:Role');
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasPermission('Delete:Role');
    }
}