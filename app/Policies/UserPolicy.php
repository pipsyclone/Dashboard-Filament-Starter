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
        return $authUser->hasPermission('ViewAny:User');
    }

    public function view(AuthUser $authUser, User $user): bool
    {
        return $authUser->hasPermission('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasPermission('Create:User');
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        return $authUser->hasPermission('Update:User');
    }

    public function delete(AuthUser $authUser, User $user): bool
    {
        return $authUser->hasPermission('Delete:User');
    }
}
