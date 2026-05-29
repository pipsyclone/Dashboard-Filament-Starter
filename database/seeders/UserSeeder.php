<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin User
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'email' => 'superadmin@example.com',
                'phone' => '1234567890',
                'password' => Hash::make('123'),
            ]
        );

        // Attach role admin
        $adminRole = Roles::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // Buat Regular User
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'username' => 'user',
                'email' => 'test@example.com',
                'phone' => '0987654321',
                'password' => Hash::make('123'),
            ]
        );

        // Attach role user
        $userRole = Roles::where('slug', 'user')->first();
        if ($userRole) {
            $user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        // Buat Guest User
        $guest = User::firstOrCreate(
            ['email' => 'guest@example.com'],
            [
                'name' => 'Guest User',
                'username' => 'guest',
                'email' => 'guest@example.com',
                'phone' => '0987654321',
                'password' => Hash::make('123'),
            ]
        );

        // Attach role guest
        $guestRole = Roles::where('slug', 'guest')->first();
        if ($guestRole) {
            $guest->roles()->syncWithoutDetaching([$guestRole->id]);
        }
    }
}
