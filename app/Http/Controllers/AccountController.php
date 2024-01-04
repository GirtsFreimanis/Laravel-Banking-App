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

        return view('open-account', [
            'currencies' => $currencies,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required',
            'type' => 'required',
        ]);
        $iban = (int)str_pad(mt_rand(0, 9999999999999), 13, '0', STR_PAD_LEFT);

        $account = new Account([
            'user_id' => $request->user()->id,
            'IBAN' => $iban,
            'currency' => $request->get('currency'),
            'balance' => 1000,
            'type' => $request->get('type'),
        ]);

        $account->save();

        return redirect()->route('dashboard')->with('success', 'Successfully created account');
    }
}
