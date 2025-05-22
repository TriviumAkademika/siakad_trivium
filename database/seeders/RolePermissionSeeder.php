<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Bikin roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $dosen = Role::firstOrCreate(['name' => 'dosen']);

        // Bikin permissions
        $permissions = ['create post', 'edit post', 'delete post'];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Kasih permission ke role
        $admin->givePermissionTo(Permission::all());
        $dosen->givePermissionTo(['create post', 'edit post']);
        $mahasiswa->givePermissionTo(['create post']);
    }
}
