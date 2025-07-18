<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            SubjectSeeder::class,
        ]);

        $headmasterRoleId = \App\Models\Role::where('name', 'headmaster')->first()->id;
        $itRoleId = \App\Models\Role::where('name', 'it')->first()->id ?? \App\Models\Role::firstOrCreate(['name' => 'it'])->id;
        \App\Models\User::factory()->create([
            'name' => 'Headmaster',
            'email' => 'headmaster@school.com',
            'password' => bcrypt('password'),
            'role_id' => $headmasterRoleId,
        ]);
        \App\Models\User::factory()->create([
            'name' => 'IT Department',
            'email' => 'it@school.com',
            'password' => bcrypt('password'),
            'role_id' => $itRoleId,
        ]);

        // Seed permissions
        $permissions = [
            'manage_users',
            'manage_roles',
            'manage_departments',
            'manage_settings',
            'view_logs',
        ];
        foreach ($permissions as $perm) {
            \App\Models\Permission::firstOrCreate(['name' => $perm]);
        }
        // Assign all permissions to IT, some to headmaster
        $itRole = \App\Models\Role::where('name', 'it')->first();
        $headmasterRole = \App\Models\Role::where('name', 'headmaster')->first();
        $allPerms = \App\Models\Permission::all();
        $itRole->permissions()->sync($allPerms->pluck('id'));
        $headmasterRole->permissions()->sync($allPerms->whereIn('name', ['manage_users', 'manage_roles', 'manage_departments', 'manage_settings'])->pluck('id'));
    }
}
