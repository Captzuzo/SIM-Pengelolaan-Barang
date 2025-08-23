<?php

namespace App\Policies;

use App\Models\MutasiStokModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MutasiStokPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        if ($user->hasPermissionTo('View Mutasi')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MutasiStokModel $mutasiStokModel): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        if ($user->hasPermissionTo('View Mutasi')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        if ($user->hasPermissionTo('Create Mutasi')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MutasiStokModel $mutasiStokModel): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        if ($user->hasPermissionTo('Edit Mutasi')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MutasiStokModel $mutasiStokModel): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        if ($user->hasPermissionTo('Delete Mutasi')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MutasiStokModel $mutasiStokModel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MutasiStokModel $mutasiStokModel): bool
    {
        return false;
    }
}
