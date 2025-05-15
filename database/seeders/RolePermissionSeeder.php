<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'request leave',
            'view own leave',
            'approve leave',
            'reject leave',
            'view all leaves',
            'generate reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);

        // Assign permissions to roles
        $adminRole->syncPermissions([
            'approve leave',
            'reject leave',
            'view all leaves',
            'generate reports',
        ]);

        $employeeRole->syncPermissions([
            'request leave',
            'view own leave',
        ]);
    }
}
