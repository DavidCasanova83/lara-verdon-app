<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@verdon-tourisme.com',
            'password' => Hash::make('SuperAdmin123!'),
            'role' => 'super-admin',
            'email_verified_at' => now(),
        ]);

        // Create additional test users with different roles
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@verdon-tourisme.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Test',
            'email' => 'user@verdon-tourisme.com',
            'password' => Hash::make('User123!'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
