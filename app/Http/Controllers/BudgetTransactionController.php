<?php

namespace App\Http\Controllers;

use App\Models\BudgetTransaction;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BudgetTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = BudgetTransaction::with(['budget', 'category'])->latest()->get();
        $budgets = Budget::all();
        $categories = Category::all();
        $rawTransactions = Transaction::all();

        return view('budget', compact('transactions', 'budgets', 'categories', 'rawTransactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budgets.create', compact('budgets', 'categories', 'transactions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'category_id' => 'required|exists:categories,id',
            // 'transaction_id' => 'required|exists:transactions,id',
            'used_amount' => 'required|numeric|min:0',
        ]);

        BudgetTransaction::create($request->all());

        return redirect()->back()->with('success', 'Transaksi anggaran berhasil ditambahkan');    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BudgetTransaction $budgetTransaction)
    {

        $budgets = Budget::all();
        $categories = Category::all();
        $transactions = Transaction::all();
        return view('budgets.edit', compact('budgetTransaction', 'budgets', 'categories', 'transactions'));
    }

    public function update(Request $request, BudgetTransaction $budgetTransaction)
    {
        // dd($request->all());

        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'category_id' => 'required|exists:categories,id',
            // 'transaction_id' => 'required|exists:transactions,id',
            'used_amount' => 'required|numeric|min:0',
        ]);

        $budgetTransaction->update([
            'budget_id'         => $request->budget_id,
            'category_id'       => $request->category_id,
            // 'transaction_id'    => $request->transaction_id,
            'used_amount'       => $request->used_amount,
        ]);

        return redirect()->back()->with('success', 'Transaksi anggaran berhasil diupdate');
    }

    public function destroy(BudgetTransaction $budgetTransaction)
    {

        $budgetTransaction->delete();
        return redirect()->route('budgets')->with('success', 'Transaksi anggaran berhasil dihapus!');
    }

     
}
