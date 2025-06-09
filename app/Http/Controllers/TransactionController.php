<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->with('category')->get();

        $transactions = Transaction::with('category')->get();
        $categories = Category::all(); 

        return view('transactions', compact('transactions', 'categories'));   

    }

    public function create()
    {
        return view('transactions.create', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'date'        => 'required|date',
        ]);

        Transaction::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()->route('transactions')->with('success', 'Transaksi berhasil ditambahkan!');

    }

   public function edit(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'date'        => 'required|date',
        ]);

        $transaction->update([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()->route('transactions')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $transaction->delete();

        return redirect()->route('transactions')->with('success', 'Transaksi berhasil dihapus!');
    }

    private function authorizeTransaction(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}