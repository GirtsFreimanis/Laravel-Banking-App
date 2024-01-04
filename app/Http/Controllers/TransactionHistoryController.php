<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function transactionHistoryForm(Request $request)
    {
        $accounts = $this->getUserAccounts($request);
        return view('transactions/transaction-history', [
            'accounts' => $accounts,
        ]);
    }

    public function transactionHistorySearch(Request $request)
    {
        $request->validate([
            'search_account' => 'required',
        ]);

        $accounts = $this->getUserAccounts($request);

        try {
            $this->authorizeAccountSearch($request);
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Invalid account number');
        }

        $query = Transaction::query();

        $query->where(function ($query) use ($request) {
            $query->where('account_from', $request->get('search_account'))
                ->orWhere('account_to', $request->get('search_account'));
        });

        if ($request->get('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->get('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $transactions = $query->orderBy('id', 'desc')->get();
        foreach ($transactions as $transaction) {
            $transaction->amount = $transaction->amount / 100;
            $transaction->exchanged_amount = $transaction->exchanged_amount / 100;
            $transaction->account_from_balance = $transaction->account_from_balance / 100;
            $transaction->account_to_balance = $transaction->account_to_balance / 100;
        }

        return view('transactions/transaction-history', [
            'accounts' => $accounts,
            'transactions' => $transactions,
            'search_account' => $request->get('search_account'
            ),
        ]);
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

    public function authorizeAccountSearch(Request $request)
    {
        $searchAccount = Account::query()
            ->where('IBAN', $request->get('search_account'))
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$searchAccount) {
            throw new Exception('Invalid account number.');
        }
    }
}
