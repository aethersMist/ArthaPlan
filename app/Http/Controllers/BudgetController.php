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
        $budgets = Budget::with(['budgetTransaction.category'])
                ->where('user_id', $user->id)
                ->orderByDesc('start_date')
                ->get();

        $currentDate = now()->translatedFormat('l, d F Y');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

         $allUserTransactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        $totalOutcomeB = $allUserTransactions->where('category.type', 'outcome')->sum('amount');
            
        // Calculate total income, outcome, and balance
        if ($allUserTransactions->isEmpty()) {
            $totalIncome = 0;
            $totalOutcome = 0;
            $totalBalance = 0;
        } else {
            $totalIncome = $allUserTransactions->where('category.type', 'income')->sum('amount');
            $totalOutcome = $allUserTransactions->where('category.type', 'outcome')->sum('amount');
            $totalBalance = $totalIncome - $totalOutcome;
        }

        // Budget Amount - Rata-Rata Harian - Anggaran
        $totalBudgetAmount = $budgets->sum('amount');
        $persenPakai = $totalBudgetAmount > 0 ? round(($totalOutcome / $totalBudgetAmount) * 100) : 0;
        $persenSisa = 100 - $persenPakai;
        $Sisa = max(0, $totalBudgetAmount - $totalOutcome);
        $statusAnggaran = $Sisa <= 0 ? 'Melebihi' : 'Sisa';

        $startDate = $budgets->min('start_date');
        $endDate = $budgets->max('end_date');
        $days = \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1;

        $rataRataHarianOutcome = $days > 0 ? $totalOutcome / $days : 0;

        $categories = Category::all();

        // Budget Transaction
        foreach ($budgets as $budget) {
            foreach ($budget->budgetTransaction as $bt) {
                $limit = $bt->used_amount;
                $categoryId = $bt->category_id;

                $totalOutcome = Transaction::where('user_id', $user->id)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->where('category_id', $categoryId)
                    ->sum('amount');

                $remaining = $limit - $totalOutcome;
                $progress = $limit > 0 ? min(100, ($totalOutcome / $limit) * 100) : 0;

                $bt->limit = $limit;
                $bt->totalOutcome = $totalOutcome;
                $bt->remaining = $remaining;
                $bt->progress = $progress;
            }
        }


        return view('budgets', compact(
            'budgets', 'categories', 'currentDate', 'totalIncome', 'totalOutcome', 'totalBalance', 'totalOutcomeB',
            'totalBudgetAmount', 'persenSisa', 'Sisa', 'persenPakai', 'statusAnggaran', 'rataRataHarianOutcome', 'transactions',
        ));
    }

    public function create()
    {
        return view('budgets.create');
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
        return view('budgets.edit');
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
