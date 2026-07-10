<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ViewAny:Roles');
    }

    public function view(User $user, $model): bool
    {
        return auth()->check() && $user->hasPermission('View:Roles');
    }

    public function create(User $user): bool
    {
        return auth()->check() && $user->hasPermission('Create:Roles');
    }

    public function update(User $user, $model): bool
    {
        return auth()->check() && $user->hasPermission('Update:Roles');
    }

    public function delete(User $user, $model): bool
    {
        return auth()->check() && $user->hasPermission('Delete:Roles');
    }

    public function deleteAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('DeleteAny:Roles');
    }

    public function restore(User $user, $model): bool
    {
        return auth()->check() && $user->hasPermission('Restore:Roles');
    }

    public function restoreAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('RestoreAny:Roles');
    }

    public function forceDelete(User $user, $model): bool
    {
        return auth()->check() && $user->hasPermission('ForceDelete:Roles');
    }

    public function forceDeleteAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ForceDeleteAny:Roles');
    }
}