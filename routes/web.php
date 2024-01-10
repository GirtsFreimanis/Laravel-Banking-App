<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CryptoCurrencyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionHistoryController;
use App\Models\Account;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::resource('account', AccountController::class);
    Route::get('/accounts-overview', [AccountController::class, 'overview'])->name('account.overview');

    Route::resource('transactions', TransactionController::class);
    Route::get('/new-transaction', [TransactionController::class, 'newTransaction'])->name('new.transaction');
    Route::get('/transaction-history', [TransactionHistoryController::class, 'transactionHistoryForm'])->name('transaction.history');
    Route::get('/transaction-history/search', [TransactionHistoryController::class, 'transactionHistorySearch'])->name('transaction.history.search');

    Route::resource('cryptocurrency', CryptoCurrencyController::class);
    Route::get('/crypto/market', [CryptoCurrencyController::class, 'market'])->name('crypto.market');
    Route::get('/crypto/market/search', [CryptoCurrencyController::class, 'search'])->name('crypto.search');
    Route::get('/crypto/buy', [CryptoCurrencyController::class, 'buyForm'])->name('crypto.buy');
    Route::get('/crypto/sell/select-account', [CryptoCurrencyController::class, 'sellForm'])->name('crypto.sell');
    Route::get('/crypto/sell/holdings', [CryptoCurrencyController::class, 'showHoldings'])->name('crypto.sellcrypto');
    Route::get('/crypto/sell/transaction', [CryptoCurrencyController::class, 'sell'])->name('crypto.sell.transaction');
    Route::get('/crypto/portfolio', [CryptoCurrencyController::class, 'portfolio'])->name('crypto.portfolio');
    Route::get('/crypto/portfolio/search', [CryptoCurrencyController::class, 'portfolioSearch'])->name('crypto.portfolio.search');
});

require __DIR__ . '/auth.php';
