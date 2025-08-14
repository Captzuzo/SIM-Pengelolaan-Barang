<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $permissions = [
            ['name' => 'Create User'],
            ['name' => 'Create Role'],
            ['name' => 'Create Permission'],
            ['name' => 'Create Kategori'],
            ['name' => 'Create Barang'],
            ['name' => 'Create Barang Beli'],
            ['name' => 'Create Barang Jual'],
            ['name' => 'Create Stok Barang'],
            ['name' => 'Create Pelanggan'],
            ['name' => 'Create Supplier'],
            ['name' => 'Create Penjualan'],
            ['name' => 'Edit User'],
            ['name' => 'Edit Role'],
            ['name' => 'Edit Permission'],
            ['name' => 'Edit Kategori'],
            ['name' => 'Edit Barang'],
            ['name' => 'Edit Barang Beli'],
            ['name' => 'Edit Barang Jual'],
            ['name' => 'Edit Stok Barang'],
            ['name' => 'Edit Pelanggan'],
            ['name' => 'Edit Supplier'],
            ['name' => 'Edit Penjualan'],
            ['name' => 'Delete User'],
            ['name' => 'Delete Role'],
            ['name' => 'Delete Permission'],
            ['name' => 'Delete Kategori'],
            ['name' => 'Delete Barang'],
            ['name' => 'Delete Barang Beli'],
            ['name' => 'Delete Barang Jual'],
            ['name' => 'Delete Stok Barang'],
            ['name' => 'Delete Pelanggan'],
            ['name' => 'Delete Supplier'],
            ['name' => 'Delete Penjualan'],
            ['name' => 'View User'],
            ['name' => 'View Role'],
            ['name' => 'View Permission'],
            ['name' => 'View Kategori'],
            ['name' => 'View Barang'],
            ['name' => 'View Barang Beli'],
            ['name' => 'View Barang Jual'],
            ['name' => 'View Stok Barang'],
            ['name' => 'View Pelanggan'],
            ['name' => 'View Supplier'],
            ['name' => 'View Penjualan'],
            ['name' => 'View Pelanggan Hutang'],
            ['name' => 'View Laporan Laba'],
            ['name' => 'View Laporan Stok'],
            ['name' => 'View Laporan Harian'],
            ['name' => 'View Laporan Bulanan'],
            ['name' => 'View Laporan Tahunan'],
        ];

        foreach ($permissions as &$permission) {
            $permission['guard_name'] = 'web';
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
        }

        DB::table('permissions')->insert($permissions);
    }
}