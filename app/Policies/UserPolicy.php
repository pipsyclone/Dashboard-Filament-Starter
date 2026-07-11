<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return auth()->check() && $authUser->hasPermission('ViewAny:User');
    }

    public function view(AuthUser $authUser, User $user): bool
    {
        return auth()->check() && $authUser->hasPermission('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return auth()->check() && $authUser->hasPermission('Create:User');
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        return auth()->check() && $authUser->hasPermission('Update:User');
    }

    public function delete(AuthUser $authUser, User $user): bool
    {
        return auth()->check() && $authUser->hasPermission('Delete:User');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return auth()->check() && $authUser->hasPermission('DeleteAny:User');
    }

    public function restore(AuthUser $authUser, User $user): bool
    {
        return auth()->check() && $authUser->hasPermission('Restore:User');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return auth()->check() && $authUser->hasPermission('RestoreAny:User');
    }

    public function forceDelete(AuthUser $authUser, User $user): bool
    {
        return auth()->check() && $authUser->hasPermission('ForceDelete:User');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return auth()->check() && $authUser->hasPermission('ForceDeleteAny:User');
    }
}
