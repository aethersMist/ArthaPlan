<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Budget;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all() as $user) {
            Budget::create([
                'user_id' => $user->id,
                'amount' => rand(1, 20) * 50000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
            ]);
        }
    }
}