<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetTransactionController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routing setelah login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/transactions', TransactionController::class);

    Route::get('/reports/export-csv', [ReportController::class, 'exportAll'])->name('reports.export.all');
    Route::resource('/reports', ReportController::class)->except(['show']);

    // Route::resource('/reports', ReportController::class);
    Route::resource('/budgets', BudgetController::class);
    Route::resource('/categories', CategoryController::class);

    Route::get('/auth/callback', [AuthenticatedSessionController::class, 'callback'])->middleware('/');
    Route::put('/update-password', [PasswordController::class, 'updateCustom'])->name('password.update.custom');
});

Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/get-by-type', [CategoryController::class, 'getCategories']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::resource('/reports', ReportController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

Route::middleware('auth')->group(function () {
    Route::resource('/transactions', TransactionController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('/budgets', BudgetController::class);
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::put('/budgets/{id}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('/budget-transactions', BudgetTransactionController::class);
    Route::get('/budgetTransactions', [BudgetTransactionController::class, 'index'])->name('budgetTransactions');
    Route::post('/budgetTransactions', [BudgetTransactionController::class, 'store'])->name('budgetTransactions.store');
    Route::put('/budgetTransactions/{budgetTransaction}', [BudgetTransactionController::class, 'update'])->name('budgetTransactions.update');
    Route::delete('/budgetTransactions/{budgetTransaction}', [BudgetTransactionController::class, 'destroy'])->name('budgetTransactions.destroy');
});


Route::middleware('auth')->group(function () {
    Route::resource('/categories', CategoryController::class);
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Routing profil user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__.'/auth.php';
