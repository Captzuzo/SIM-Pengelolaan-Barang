<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('View Supplier')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supplier $supplier): bool
    {
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

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Create Supplier')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Supplier $supplier): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Edit Supplier')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Delete Supplier')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Supplier $supplier): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return false;
    }
}