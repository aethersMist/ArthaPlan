<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\BudgetTransaction;

class BudgetTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (Budget::all() as $budget) {
            $outcomeCategories = $budget->user->categories()->where('type', 'outcome')->get();

            if ($outcomeCategories->count() >= 2) {
                foreach ($outcomeCategories->random(2) as $category) {
                    BudgetTransaction::create([
                        'budget_id' => $budget->id,
                        'category_id' => $category->id,
                        'used_amount' => rand(1, 20) * 50000,
                    ]);
                }
            }
        }
    }
}