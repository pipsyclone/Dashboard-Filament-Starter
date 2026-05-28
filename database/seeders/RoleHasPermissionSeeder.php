<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Roles;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleHasPermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin mendapat semua permission
        $adminRole = Roles::where('slug', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(
                Permissions::pluck('id')->toArray()
            );
        }

        // User hanya view permission
        $userRole = Roles::where('slug', 'user')->first();
        if ($userRole) {
            $viewPermissions = Permissions::whereIn('slug', [
                'view_any_user',
                'view_user',
            ])->pluck('id')->toArray();
            
            $userRole->permissions()->sync($viewPermissions);
        }

        // Guest tidak ada permission
        $guestRole = Roles::where('slug', 'guest')->first();
        if ($guestRole) {
            $guestRole->permissions()->sync([]);
        }
    }
}
