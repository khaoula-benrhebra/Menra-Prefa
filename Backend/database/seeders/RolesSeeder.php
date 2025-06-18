<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom' => 'Client'],
            ['nom' => 'Agent commercial'],
            ['nom' => 'Responsable production'],
            ['nom' => 'Admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}