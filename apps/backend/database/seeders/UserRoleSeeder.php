<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadminUser = User::where('email', 'haidarbahzi07@gmail.com')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();
        $regularUser = User::where('email', 'user@example.com')->first();

        $superadminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($superadminUser && $superadminRole) {
            $superadminUser->roles()->syncWithoutDetaching([$superadminRole->id]);
        }

        if ($adminUser && $adminRole) {
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        if ($regularUser && $userRole) {
            $regularUser->roles()->syncWithoutDetaching([$userRole->id]);
        }
    }
}
