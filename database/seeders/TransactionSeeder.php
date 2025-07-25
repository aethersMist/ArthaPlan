<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (User::all() as $user) {
            $categories = $user->categories;

            foreach ($categories->random(5) as $category) {
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'amount' => rand(1, 20) * 50000,
                    'description' => 'Pengeluaran untuk ' . $category->name,
                    'date' => Carbon::now()->subDays(rand(1, 20)),
                ]);
            }
        }
    }
}
