<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if an admin already exists so it doesn't duplicate if you run it twice
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'System Admin',
                'email' => 'abrargulzar2004@servicehub.com',       // 🔑 Change this to your Admin Email
                'password' => Hash::make('123456789'), // 🔑 Change this to your Admin Password
                'role' => 'admin',                        // Matches your database role column
                'email_verified_at' => now(),             // Instantly marks them as verified!
            ]);
        }
    }
}