<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetTransaction extends Model
{
    protected $fillable = [
        'budget_id',
        'category_id',
        'transaction_id',
        'used_amount'
    ];


    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
