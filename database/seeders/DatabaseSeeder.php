<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin1',
            'email' => 'admin1@admin.com',
        ]);

        $user1 = User::factory()->create([
            'name' => 'Kasir',
            'email' => 'kasir@kasir.com',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);

        $role1 = Role::create(['name' => 'Kasir']);
        $user1->assignRole($role1);
    }
}