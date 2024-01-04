<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function newTransaction(Request $request)
    {
        $accounts = $this->getUserAccounts($request);

        return view('transactions/new-transaction', [
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $fromAccount = Account::query()
            ->where('IBAN', $request->input('account_from'))
            ->first();

        $request->validate([
            'account_from' => 'required',
            'account_to' => 'required|digits:13|not_in:' . $request->input('account_from'),
            'amount' => 'required|numeric|min:1|max:' . $fromAccount->balance,
        ]);

        $toAccount = Account::query()
            ->where('IBAN', $request->input('account_to'))
            ->first();

        if (!$toAccount) {
            return back()->with('error', 'Transaction failed: Receiver Account not found.');
        }

        $fromAccount->update([
            'balance' => $fromAccount->balance - $request->input('amount') * 100,
        ]);

        $fromAccountCurrencyRate = Currency::query()
            ->where('symbol', $fromAccount->currency)
            ->first()
            ->rate;

        $toAccountCurrencyRate = Currency::query()
            ->where('symbol', $toAccount->currency)
            ->first()
            ->rate;

        $fromExchangeRate = $request->input('amount') / ($fromAccountCurrencyRate / 100);

        $exchangedAmount = floor($fromExchangeRate * ($toAccountCurrencyRate));

        $toAccount->update([
            'balance' => $toAccount->balance + $exchangedAmount,
        ]);

        $transaction = (new Transaction)->fill([
            'amount' => $request->input('amount') * 100,
            'exchanged_amount' => $exchangedAmount,
            'account_from' => $request->input('account_from'),
            'account_to' => $request->input('account_to'),
            'currency_from' => $fromAccount->currency,
            'currency_to' => $toAccount->currency,
            'account_from_balance' => $fromAccount->balance,
            'account_to_balance' => $toAccount->balance,
        ]);

        $transaction->save();

        return redirect()->route('dashboard')->with('success', 'Transaction successful.');
    }

    public function getUserAccounts(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->get();

        foreach ($accounts as $account) {
            $account->balance = $account->balance / 100;
        }

        return $accounts;
    }
}
