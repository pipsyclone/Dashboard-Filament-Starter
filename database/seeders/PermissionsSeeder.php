<?php

namespace Database\Seeders;

use App\Models\Permissions;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [

            'Setting' => [
                'ViewAny' => 'Mengatur pengaturan aplikasi',
            ],

            'User' => [
                'ViewAny' => 'Melihat daftar user',
                'View'     => 'Melihat detail user',
                'Create'   => 'Membuat user baru',
                'Update'   => 'Mengubah user',
                'Delete'   => 'Menghapus user',
            ],

            'Role' => [
                'ViewAny' => 'Melihat daftar role',
                'View'     => 'Melihat detail role',
                'Create'   => 'Membuat role baru',
                'Update'   => 'Mengubah role',
                'Delete'   => 'Menghapus role',
            ],

            'DatabaseBackup' => [
                'ViewAny' => 'Melihat daftar backup database',
                'Create'   => 'Membuat backup database baru',
                'Delete'   => 'Menghapus backup database',
            ],

            'LogAktivitas' => [
                'ViewAny' => 'Melihat daftar log aktivitas',
            ],

        ];

        foreach ($resources as $resource => $actions) {

            foreach ($actions as $action => $description) {

                Permissions::firstOrCreate(
                    [
                        'slug' => str($action . '_' . $resource)
                            ->snake()
                            ->lower(),
                    ],
                    [
                        'name' => "{$action}:{$resource}",
                        'slug' => str($action . '_' . $resource)
                            ->snake()
                            ->lower(),
                        'description' => $description,
                    ]
                );
            }
        }
    }
}
