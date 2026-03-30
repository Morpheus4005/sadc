<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            'view-dashboard',
            'manage-students',
            'import-data',
            'export-data',
            'view-logs',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $staffRole->givePermissionTo([
            'view-dashboard',
            'manage-students',
            'import-data',
            'export-data',
        ]);
        
        $viewerRole->givePermissionTo([
            'view-dashboard',
            'export-data',
        ]);

        // Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@binus.ac.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        $staff = User::firstOrCreate(
            ['email' => 'staff@binus.ac.id'],
            [
                'name' => 'Staff SRSC',
                'password' => Hash::make('password'),
            ]
        );
        $staff->assignRole('staff');

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@binus.ac.id'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('password'),
            ]
        );
        $viewer->assignRole('viewer');

        $this->command->info('✓ Permissions created');
        $this->command->info('✓ Roles created');
        $this->command->info('✓ Users created');
        $this->command->info('');
        $this->command->info('Default users:');
        $this->command->info('Admin: admin@binus.ac.id / password');
        $this->command->info('Staff: staff@binus.ac.id / password');
        $this->command->info('Viewer: viewer@binus.ac.id / password');
    }
}