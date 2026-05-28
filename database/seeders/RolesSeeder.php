<?php

namespace Database\Seeders;

use App\Models\Roles;    

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator dengan akses penuh',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'User biasa dengan akses terbatas',
            ],
            [
                'name' => 'Guest',
                'slug' => 'guest',
                'description' => 'Guest hanya bisa melihat',
            ],
        ];

        foreach ($roles as $role) {
            Roles::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
