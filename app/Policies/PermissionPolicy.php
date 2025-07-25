<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */

    public function viewAny(User $user): bool
    {
        // if ($user->hasPermissionTo('View Permission')) {
        //     return true;
        // }
        // return false;
        // return $user->hasRole('Admin');

        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('View Permission')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // return $user->hasRole('Admin');
        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Create Permission')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        // return $user->hasRole('Admin');
        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Edit Permission')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        // return $user->hasRole('Admin');
        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Delete Permission')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return false;
    }
}
