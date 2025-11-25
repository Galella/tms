<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',

            // Dashboard permissions
            'view-dashboard',

            // Truck operations permissions
            'create-truck-in',
            'view-truck-in',
            'create-truck-out',
            'view-truck-out',

            // Terminal permissions
            'view-terminals',
            'manage-terminals',

            // Container permissions
            'view-containers',
            'manage-containers',
            'create-containers',
            'edit-containers',
            'delete-containers',
            // Inventory permissions
            'view-inventory',
            'manage-inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $terminalManagerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'terminal-manager']);
        $terminalManagerRole->syncPermissions([
            'view-users',
            'view-dashboard',
            'create-truck-in',
            'view-truck-in',
            'create-truck-out',
            'view-truck-out',
            'view-containers',
            'manage-containers',
            'create-containers',
            'edit-containers',
            'view-inventory',
            'manage-inventory',
        ]);

        $operatorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'operator']);
        $operatorRole->syncPermissions([
            'view-dashboard',
            'create-truck-in',
            'view-truck-in',
            'create-truck-out',
            'view-truck-out',
            'view-containers',
            'view-inventory',
        ]);
    }
}
