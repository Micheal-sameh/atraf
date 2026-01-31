<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Etraf permissions
            'view-etraf',
            'create-etraf',
            'edit-etraf',
            'delete-etraf',
            'change-etraf-status',

            // User permissions
            'view-users',
            'manage-users',
            'assign-roles',

            // Father schedule permissions
            'view-schedules',
            'create-schedules',
            'delete-schedules',

            // Family permissions
            'view-families',

            // Settings permissions
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $fatherRole = Role::firstOrCreate(['name' => 'father']);
        $fatherRole->givePermissionTo([
            'view-etraf',
            'create-etraf',
            'change-etraf-status',
            'view-users',
            'view-schedules',
            'create-schedules',
            'delete-schedules',
            'view-families',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view-etraf',
            'create-etraf',
        ]);
    }
}
