<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function create()
    {
        $currencies = config('currencies');

        return view('account.open-account', [
            'currencies' => $currencies,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required',
            'type' => 'required',
        ]);
        $iban = '';

        for ($i = 0; $i < 13; $i++) {
            $iban .= rand(1, 9);
        }
        $iban = (int)$iban;

        $account = new Account([
            'user_id' => $request->user()->id,
            'IBAN' => $iban,
            'currency' => $request->get('currency'),
            'balance' => 10000,
            'type' => $request->get('type'),
        ]);

        $account->save();

        return redirect()->route('dashboard')->with('success', 'Successfully created account');
    }

    public function overview(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->get();

        return view('account.overview', [
            'accounts' => $accounts,
        ]);
    }
}
