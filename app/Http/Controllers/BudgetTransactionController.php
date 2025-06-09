<?php

namespace App\Http\Controllers;

use App\Models\BudgetTransaction;
use Illuminate\Http\Request;

class BudgetTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = BudgetTransaction::all();
        return view('budgets', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BudgetTransaction $budgetTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetTransaction $budgetTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BudgetTransaction $budgetTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BudgetTransaction $budgetTransaction)
    {
        //
    }
}
