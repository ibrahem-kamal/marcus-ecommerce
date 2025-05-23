<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (!Admin::query()->where('email', 'admin@example.com')->exists()) {
            // Create admin user
            $admin = Admin::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);

            // Create a personal access token for the admin
            $admin->createToken('admin-token');
        } 
    }
}
