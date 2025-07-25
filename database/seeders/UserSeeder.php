<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create an admin user
        User::create([
            'name' => 'Admin',
            'email' => 'orellaja@students.amikom.ac.id',
            'password' => Hash::make("asdfghjkl;'"),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
