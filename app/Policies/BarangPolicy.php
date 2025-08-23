<?php

namespace App\Policies;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarangPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // if ($user->hasPermissionTo('View Barang')) {
        //     return true;
        // }
        // return false;

        // Jika role Admin, izinkan semua
        if ($user->hasRole(['Admin', 'Kasir'])) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('View Barang')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Barang $barang): bool
    {
         if ($user->hasRole('Admin') || $user->hasRole('Kasir')) {
            return true;
        }

        if ($user->hasPermissionTo('View Barang')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // if ($user->hasPermissionTo('Create')) {
        //     return true;
        // }
        // return false;

        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // if ($user->hasRole('Kasir')) {
        //     return true;
        // }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Create Barang')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Barang $barang): bool
    {
        // if ($user->hasPermissionTo('Edit')) {
        //     return true;
        // }
        // return false;

        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Edit Barang')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Barang $barang): bool
    {
        // if ($user->hasPermissionTo('Delete')) {
        //     return true;
        // }
        // return false;

        // Jika role Admin, izinkan semua
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Jika bukan admin, cek permission tertentu
        if ($user->hasPermissionTo('Delete Barang')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Barang $barang): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Barang $barang): bool
    {
        return false;
    }
}
