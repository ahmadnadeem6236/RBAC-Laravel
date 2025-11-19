<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $projectRole = Role::create(['name' => 'project_access']);
        $managerRole = Role::create(['name' => 'manager']);
        $adminRole = Role::create(['name' => 'admin']);
        
        // Create users
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password')
        ]);
        $user1->roles()->attach($projectRole);
        
        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password')
        ]);
        $user2->roles()->attach($managerRole);
        
        $user3 = User::create([
            'name' => 'User Three',
            'email' => 'user3@example.com',
            'password' => Hash::make('password')
        ]);
        $user3->roles()->attach($adminRole);
    }
}
