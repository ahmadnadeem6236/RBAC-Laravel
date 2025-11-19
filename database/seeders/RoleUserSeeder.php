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
        // Create roles (only if they don't exist)
        $projectRole = Role::firstOrCreate(['name' => 'project_access']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Create users (only if they don't exist)
        $user1 = User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User One',
                'password' => Hash::make('password')
            ]
        );
        if (!$user1->roles->contains($projectRole->id)) {
            $user1->roles()->attach($projectRole);
        }
        
        $user2 = User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'User Two',
                'password' => Hash::make('password')
            ]
        );
        if (!$user2->roles->contains($managerRole->id)) {
            $user2->roles()->attach($managerRole);
        }
        
        $user3 = User::firstOrCreate(
            ['email' => 'user3@example.com'],
            [
                'name' => 'User Three',
                'password' => Hash::make('password')
            ]
        );
        if (!$user3->roles->contains($adminRole->id)) {
            $user3->roles()->attach($adminRole);
        }
    }
}
