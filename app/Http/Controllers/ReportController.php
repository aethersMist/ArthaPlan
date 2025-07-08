<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class ReportController extends Controller
{
    public function index()
    {

        // income
        $incomeByCategory = Transaction::where('user_id', Auth::id())->with('category')
            ->whereHas('category', fn($q) => $q->where('type', 'income'))
            ->get()
            ->groupBy(fn($transaction) => $transaction->category->name)
            ->map(fn($group) => $group->sum('amount'));

        $categoriesIncome = $incomeByCategory->keys()->toArray();
        $valuesIncome = $incomeByCategory->values()->toArray();

        // outcome
        $outcomeByCategory = Transaction::where('user_id', Auth::id())->with('category')
            ->whereHas('category', fn($q) => $q->where('type', 'outcome'))
            ->get()
            ->groupBy(fn($transaction) => $transaction->category->name)
            ->map(fn($group) => $group->sum('amount'));
        $categoriesOutcome = $outcomeByCategory->keys()->toArray();
        $valuesOutcome = $outcomeByCategory->values()->toArray();

        // Daigram Bar
        $filter = 'bulan';
        $selectedDate = request()->input('date', now()->format('d F Y'));
        $date = Carbon::parse($selectedDate);

        list($labels, $dataOut, $dataIn) = $this->getChartData('bulan', $date);

        $transactionsDate = Transaction::where('user_id', Auth::id())->with('category')
            ->whereYear('date', $date->year)
            ->get();

        return view('reports', compact(
            'categoriesIncome', 'valuesIncome', 'categoriesOutcome', 'valuesOutcome',
            'labels', 'dataOut', 'dataIn', 'filter', 'selectedDate', 'transactionsDate'
        ));

    }

    public function getChartDataApi(Request $request)
    {
        $user = Auth::user();

        $selectedDate = $request->input('date', now()->format('d F Y'));
        $date = Carbon::parse($selectedDate);

        $transactions = Transaction::where('user_id', Auth::id())->with('category')
            ->where('user_id', $user->id)
            ->whereYear('date', $date->year)
            ->get();

        list($labels, $dataOut, $dataIn) = $this->getChartData('bulan', $date);

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

        $year = now()->year;

        $labels = [];
        $dataOut = array_fill(0, 12, 0);
        $dataIn = array_fill(0, 12, 0);

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($year, $month)->translatedFormat('F');
        }

        $queryOut = Transaction::where('user_id', Auth::id())->with('category')->whereHas('category', fn($q) => $q->where('type', 'outcome'));
        $queryIn = Transaction::where('user_id', Auth::id())->with('category')->whereHas('category', fn($q) => $q->where('type', 'income'));

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
            $dataOut[$index] = $tr->total;
        }

        foreach ($transactionsIn as $tr) {
            $index = $tr->month - 1;
            $dataIn[$index] = $tr->total;
        }

        return [$labels, $dataOut, $dataIn];
    }

private function exportCSVByType($filename, $type = null)
{
    $path = storage_path("app/public/{$filename}");
    $handle = fopen($path, 'w');

    fputcsv($handle, ['Tanggal', 'Kategori', 'Tipe', 'Jumlah', 'Deskripsi']);

    $query = DB::table('transactions')
        ->join('categories', 'transactions.category_id', '=', 'categories.id')
        ->where('transactions.user_id', auth()->id())
        ->select(
            'transactions.date',
            'categories.name as category',
            'categories.type',
            'transactions.amount',
            'transactions.description'
        )
        ->orderByDesc('transactions.date');

    if ($type) {
        $query->where('categories.type', $type);
    }

    $transactions = $query->get();

    foreach ($transactions as $row) {
        fputcsv($handle, [
            $row->date,
            $row->category,
            ucfirst($row->type),
            number_format($row->amount, 0, ',', '.'),
            $row->description,
        ]);
    }

    fclose($handle);
    return response()->download($path)->deleteFileAfterSend(true);
}

public function exportAll()
{
    return $this->exportCSVByType('laporan-semua.csv');
}


}
