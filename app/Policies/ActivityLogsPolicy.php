<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ViewAny:ActivityLogs');
    }

    public function view(User $user): bool
    {
        return auth()->check() && $user->hasPermission('View:ActivityLogs');
    }

    public function delete(User $user): bool
    {
        return auth()->check() && $user->hasPermission('Delete:ActivityLogs');
    }

    public function deleteAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('DeleteAny:ActivityLogs');
    }

    public function restore(User $user): bool
    {
        return auth()->check() && $user->hasPermission('Restore:ActivityLogs');
    }

    public function restoreAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('RestoreAny:ActivityLogs');
    }

    public function forceDelete(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ForceDelete:ActivityLogs');
    }

    public function forceDeleteAny(User $user): bool
    {
        return auth()->check() && $user->hasPermission('ForceDeleteAny:ActivityLogs');
    }
}
