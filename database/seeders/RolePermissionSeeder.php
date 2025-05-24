<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // List entitas yang butuh CRUD
        $entities = [
            'user', 'dosen', 'mahasiswa', 'kelas',
            'matkul', 'jadwal', 'frs', 'waktu', 'ruangan',
        ];

        // Aksi standar CRUD
        $actions = ['create', 'read', 'update', 'delete'];

        // Buat semua permission CRUD
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "$action-$entity",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Tambahan untuk dosen/mahasiswa
        $extraPermissions = [
            'update-detail_frs',
            'read-detail_frs',
            'create-detail_frs',
            'delete-detail_frs',
            'update-nilai',
            'read-nilai',
            'read-dosen',
        ];

        foreach ($extraPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // === Roles ===

        // Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', '%-user')
                  ->orWhere('name', 'like', '%-dosen')
                  ->orWhere('name', 'like', '%-mahasiswa')
                  ->orWhere('name', 'like', '%-kelas')
                  ->orWhere('name', 'like', '%-matkul')
                  ->orWhere('name', 'like', '%-jadwal')
                  ->orWhere('name', 'like', '%-frs')
                  ->orWhere('name', 'like', '%-waktu')
                  ->orWhere('name', 'like', '%-ruangan');
        })->get();
        $admin->syncPermissions($adminPermissions);

        // Dosen
        $dosen = Role::firstOrCreate(['name' => 'dosen']);
        $dosen->syncPermissions([
            'read-mahasiswa', 'read-kelas', 'read-frs',
            'update-detail_frs', 'update-nilai', 'read-jadwal',
        ]);

        // Mahasiswa
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $mahasiswa->syncPermissions([
            'update-user',
            'read-mahasiswa',
            'read-dosen',
            'read-jadwal',
            'read-frs',
            'create-detail_frs', 'read-detail_frs', 'update-detail_frs', 'delete-detail_frs',
            'read-nilai',
        ]);
    }
}