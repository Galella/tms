<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the admin user and assign the admin role
        $adminUser = User::where('email', 'admin@example.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminUser && $adminRole) {
            $adminUser->assignRole($adminRole);
        }
    }
}
