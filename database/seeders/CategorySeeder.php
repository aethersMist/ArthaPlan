<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $categories = [
                ['name' => 'Gaji', 'type' => 'income'],
                ['name' => 'Bonus', 'type' => 'income'],
                ['name' => 'Makanan', 'type' => 'outcome'],
                ['name' => 'Transportasi', 'type' => 'outcome'],
                ['name' => 'Belanja', 'type' => 'outcome'],
            ];

            $users = User::all();

            foreach ($users as $user) {
                foreach ($categories as $cat) {
                    Category::create([
                        'user_id' => $user->id,
                        'name' => $cat['name'],
                        'type' => $cat['type'],
                    ]);
                }
            }
        }
    }
}