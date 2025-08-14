<?php

namespace App\Policies;

use App\Models\BarangBeli;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarangBeliPolicy
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
        if ($user->hasPermissionTo('View Barang Beli')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BarangBeli $barangBeli): bool
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
        if ($user->hasPermissionTo('Create Barang Beli')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BarangBeli $barangBeli): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Edit Barang Beli')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BarangBeli $barangBeli): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Delete Barang Beli')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BarangBeli $barangBeli): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BarangBeli $barangBeli): bool
    {
        return false;
    }
}