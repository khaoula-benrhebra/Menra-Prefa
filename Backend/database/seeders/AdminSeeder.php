<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nom', 'Admin')->first();
        
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Super Admin',
                    'phone' => '0600000000',
                    'password' => 'password123', 
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(), // Admin vérifié automatiquement
                ]
            );
        }
    }
}