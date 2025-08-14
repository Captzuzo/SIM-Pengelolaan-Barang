<?php

namespace App\Policies;

use App\Models\StokBarang;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StokBarangPolicy
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
        if ($user->hasPermissionTo('View Stok Barang')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StokBarang $stokBarang): bool
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
        if ($user->hasPermissionTo('Create Stok Barang')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StokBarang $stokBarang): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Edit Stok Barang')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StokBarang $stokBarang): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Delete Stok Barang')) {
            return true;
        }
        // return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StokBarang $stokBarang): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StokBarang $stokBarang): bool
    {
        return false;
    }
}