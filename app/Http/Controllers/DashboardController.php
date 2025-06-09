<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request(); 
        $user = Auth::user();
        $budgets = Budget::where('user_id', $user->id)->orderByDesc('start_date')->get();


        // Get the current month
        $currentDate = now()->translatedFormat('l, d F Y');

        // Saldo - Income - Outcome
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $allUserTransactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();
            
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

        // Anggaran -Budget
        $totalBudget = Budget::where('user_id', $user->id)->get();
        if ($totalBudget->isEmpty()) {
            $totalBudgetAmount = 0;
        } else {
            $totalBudgetAmount = $totalBudget->sum('amount');
        }
        
        // Hitung persentase sisa anggaran
        if ($totalBudgetAmount <= 0) {
            $persenSisa = 100;
            $persenPakai = 0;
        } else {
            $persenPakai = round(($totalOutcome / $totalBudgetAmount) * 100);
            $persenSisa = 100 - $persenPakai;
        }

        // Hitung sisa anggaran
        $Sisa = $totalBudgetAmount - $totalOutcome;

        // Tampilkan status anggaran
        if ($Sisa < 0) {
            $statusAnggaran = "Melebihi";
            $Sisa = 0;
        } else {
            $statusAnggaran = "Sisa";
        }

        // Rata-rata harian budget
        $startDate = $budgets->min('start_date');
        $endDate = $budgets->max('end_date');
        $days = \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1;

        $rataRataHarianOutcome = $days > 0 ? $totalOutcome / $days : 0;

        // Laporan
       $incomeByCategory = Transaction::with('category')
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->get()
            ->groupBy(fn($transaction) => $transaction->category->name)
            ->map(fn($group) => $group->sum('amount'));

        $categoriesIncome = $incomeByCategory->keys()->toArray();
        $valuesIncome = $incomeByCategory->values()->toArray();


        // Transactions 
        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        // Categories
        $categories = Category::all();

        // Ambil data untuk grafik (default: bulan ini)
        $filter = request()->input('filter', 'bulan');
        $selectedDate = request()->input('date', now()->format('d F Y'));
        $date = Carbon::parse($selectedDate);

        list($labels, $dataOut, $dataIn) = $this->getChartData($filter, $date);

        // Diagram Batang - Rata-rata Pengeluaran
        $totalOut = array_sum($dataOut);
        $rataRata = count($dataOut) ? $totalOut / count($dataOut) : 0;

        return view('dashboard', compact('categories', 'transactions', 'currentDate', 'totalIncome', 'totalOutcome', 'totalBalance',  'labels', 'valuesIncome', 'categoriesIncome' , 
            'categories', 'currentDate', 'totalIncome', 'totalOutcome', 'totalBalance',
        'dataOut','dataIn', 'filter', 'selectedDate', 'rataRata', 'totalBudget', 'persenSisa', 'Sisa', 'persenPakai', 'statusAnggaran', 'rataRataHarianOutcome', 'totalBudgetAmount'));
    }

    public function getChartDataApi(Request $request)
    {
        $user = Auth::user();

        $filter = $request->input('filter', 'bulan');
        $selectedDate = $request->input('date', now()->format('d F Y'));
        $date = Carbon::parse($selectedDate);

        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->when($filter === 'bulan', function ($query) use ($date) {
                return $query->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year);
            })
            ->when($filter === 'minggu', function ($query) use ($date) {
                $startOfWeek = now()->copy()->startOfWeek();
                $endOfWeek = now()->copy()->endOfWeek();
                return $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
            })
            ->when($filter === 'hari', function ($query) use ($date) {
                return $query->whereDate('date', now()->toDateString());
            })
            ->get();

        list($labels, $dataOut, $dataIn) = $this->getChartData($filter, $date);

        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
        $totalOutcome = $transactions->where('category.type', 'outcome')->sum('amount');
        $balance = $totalIncome - $totalOutcome;

        return response()->json([
            'labels' => $labels,
            'dataOut' => $dataOut,
            'dataIn' => $dataIn,
            'income' => $totalIncome,
            'outcome' => $totalOutcome,
            'balance' => $balance
        ]);
    }

    private function getChartData($filter, Carbon $date)
    {
        $queryOut = Transaction::with('category')->whereHas('category', fn($q) => $q->where('type', 'outcome'));
        $queryIn = Transaction::with('category')->whereHas('category', fn($q) => $q->where('type', 'income'));

        $labels = [];
        $dataOut = [];
        $dataIn = [];

        switch ($filter) {
            case 'tahun':
                $startYear = now()->copy()->year - 2;
                $endYear = now()->copy()->year;

                for ($year = $startYear; $year <= $endYear; $year++) {
                    $labels[] = $year;
                    $dataOut[] = 0;
                    $dataIn[] = 0;
                }

                $transactionsOut = $queryOut->select(
                    DB::raw('YEAR(date) as year'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [
                    Carbon::create($startYear)->startOfYear(),
                    Carbon::create($endYear)->endOfYear()
                ])->groupBy('year')->get();

                $transactionsIn = $queryIn->select(
                    DB::raw('YEAR(date) as year'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [
                    Carbon::create($startYear)->startOfYear(),
                    Carbon::create($endYear)->endOfYear()
                ])->groupBy('year')->get();

                foreach ($transactionsOut as $tr) {
                    $index = array_search($tr->year, $labels);
                    if ($index !== false) $dataOut[$index] = $tr->total;
                }

                foreach ($transactionsIn as $tr) {
                    $index = array_search($tr->year, $labels);
                    if ($index !== false) $dataIn[$index] = $tr->total;
                }
                break;

            case 'bulan':
                $year = now()->year;

                $labels = [];
                $dataOut = array_fill(0, 12, 0);
                $dataIn = array_fill(0, 12, 0);

                for ($month = 1; $month <= 12; $month++) {
                    $labels[] = Carbon::create($year, $month)->translatedFormat('F');
                }

                $transactionsOut = $queryOut->select(
                    DB::raw('MONTH(date) as month'),
                    DB::raw('SUM(amount) as total')
                )->whereYear('date', $year)
                ->groupBy('month')->get();

                $transactionsIn = $queryIn->select(
                    DB::raw('MONTH(date) as month'),
                    DB::raw('SUM(amount) as total')
                )->whereYear('date', $year)
                ->groupBy('month')->get();

                foreach ($transactionsOut as $tr) {
                    $index = $tr->month - 1;
                    if (isset($dataOut[$index])) $dataOut[$index] = $tr->total;
                }

                foreach ($transactionsIn as $tr) {
                    $index = $tr->month - 1;
                    if (isset($dataIn[$index])) $dataIn[$index] = $tr->total;
                }
                break;

            case 'minggu':
                $startOfMonth = now()->copy()->startOfMonth();
                $endOfMonth = now()->copy()->endOfMonth();

                $totalWeeks = ceil(now()->daysInMonth / 7);
                for ($i = 1; $i <= $totalWeeks; $i++) {
                    $labels[] = 'Minggu ' . $i;
                    $dataOut[] = 0;
                    $dataIn[] = 0;
                }

                $transactionsOut = $queryOut->select(
                    DB::raw('FLOOR((DAY(date) - 1) / 7) + 1 as week_of_month'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->groupBy('week_of_month')->get();

                $transactionsIn = $queryIn->select(
                    DB::raw('FLOOR((DAY(date) - 1) / 7) + 1 as week_of_month'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->groupBy('week_of_month')->get();

                foreach ($transactionsOut as $tr) {
                    $index = $tr->week_of_month - 1;
                    if (isset($dataOut[$index])) $dataOut[$index] = $tr->total;
                }

                foreach ($transactionsIn as $tr) {
                    $index = $tr->week_of_month - 1;
                    if (isset($dataIn[$index])) $dataIn[$index] = $tr->total;
                }
                break;

            case 'hari':
                $startOfWeek = now()->copy()->startOfWeek();
                $endOfWeek = now()->copy()->endOfWeek();

                $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $labels = $dayNames;
                $dataOut = array_fill(0, 7, 0);
                $dataIn = array_fill(0, 7, 0);

                $transactionsOut = $queryOut->select(
                    DB::raw('DAYOFWEEK(date) as day_of_week'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->groupBy('day_of_week')->get();

                $transactionsIn = $queryIn->select(
                    DB::raw('DAYOFWEEK(date) as day_of_week'),
                    DB::raw('SUM(amount) as total')
                )->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->groupBy('day_of_week')->get();

                foreach ($transactionsOut as $tr) {
                    $index = $tr->day_of_week - 1;
                    if (isset($dataOut[$index])) $dataOut[$index] = $tr->total;
                }

                foreach ($transactionsIn as $tr) {
                    $index = $tr->day_of_week - 1;
                    if (isset($dataIn[$index])) $dataIn[$index] = $tr->total;
                }
                break;

            default:
                // fallback ke bulan
                return $this->getChartData('bulan', $date);
        }

        return [$labels, $dataOut, $dataIn];
    }

}
