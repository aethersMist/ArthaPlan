<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetTransaction;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $budgets = Budget::where('user_id', $user->id)->orderByDesc('start_date')->get();
        $budgetTransactions = BudgetTransaction::all();

        // Budget Transaction
        $totalUsedAmount = $budgets->flatMap->budgetTransaction->sum('used_amount');

        $currentDate = now()->translatedFormat('l, d F Y');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
        $totalOutcome = $transactions->where('category.type', 'outcome')->sum('amount');
        $totalBalance = $totalIncome - $totalOutcome;

        $totalBudgetAmount = $budgets->sum('amount');

        $persenPakai = $totalBudgetAmount > 0 ? round(($totalOutcome / $totalBudgetAmount) * 100) : 0;
        $persenSisa = 100 - $persenPakai;
        $Sisa = max(0, $totalBudgetAmount - $totalOutcome);
        $statusAnggaran = $Sisa <= 0 ? 'Melebihi' : 'Sisa';

        $rataRataHarianBudget = $budgets->reduce(function ($carry, $budget) {
            $days = $budget->start_date->diffInDays($budget->end_date) + 1;
            return $carry + ($budget->amount / $days);
        }, 0);

        $categories = Category::all();
        


        return view('budgets', compact(
            'budgets', 'categories', 'currentDate', 'totalIncome', 'totalOutcome', 'totalBalance', 'totalUsedAmount',
            'totalBudgetAmount', 'persenSisa', 'Sisa', 'persenPakai', 'statusAnggaran', 'rataRataHarianBudget'
        ));
    }

    public function create()
    {
        return view('budgets.create', compact('budgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Budget::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('budgets')->with('success', 'Budget berhasil ditambahkan!');
    }


    public function edit(Budget $budget)
    {
        $this->authorizeBudget($budget);
        return view('budgets.edit', compact('budgets'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorizeBudget($budget);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $budget->update([
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('budgets')->with('success', 'Budget berhasil diperbarui!');
    }

   
    public function destroy(Budget $budget)
    {
        $this->authorizeBudget($budget);
         
        $budget->delete();

        return redirect()->route('budgets')->with('success', 'Budget berhasil dihapus!');
    }

    private function authorizeBudget(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
