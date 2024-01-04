<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CryptoCurrencyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('account', AccountController::class)->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('/crypto/market', [CryptoCurrencyController::class, 'market'])->name('crypto.market');
    Route::get('/crypto/market/search', [CryptoCurrencyController::class, 'search'])->name('crypto.search');
    Route::get('/crypto/trade', [CryptoCurrencyController::class, 'trade'])->name('crypto.trade');

    Route::resource('transactions', TransactionController::class);
    Route::get('/new-transaction', [TransactionController::class, 'newTransaction'])->name('new.transaction');
    Route::get('/transaction-history', [TransactionHistoryController::class, 'transactionHistoryForm'])->name('transaction.history');
    Route::get('/transaction-history/search', [TransactionHistoryController::class, 'transactionHistorySearch'])->name('transaction.history.search');
});

require __DIR__ . '/auth.php';
