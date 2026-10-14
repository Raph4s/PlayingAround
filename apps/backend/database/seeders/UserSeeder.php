<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Superadmin
        User::firstOrCreate(
            ['email' => 'haidarbahzi07@gmail.com'],
            [
                'fullname' => 'Super Admin',
                'password' => '1234567890',
            ]
        );

        // 2. Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'fullname' => 'Admin Operasional',
                'password' => '1234567890',
            ]
        );

        // 3. User
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'fullname' => 'Pengguna Biasa',
                'password' => '1234567890',
            ]
        );
    }
}
