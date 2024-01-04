<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCurrency;
use Illuminate\Http\Request;

class CryptoCurrencyController extends Controller
{
    public function market()
    {
        $cryptoCurrencies = CryptoCurrency::query()->orderBy('price', 'desc')->paginate(15);
        return view('crypto.market', [
            'cryptoCurrencies' => $cryptoCurrencies,
        ]);
    }

    public function trade(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        $cryptoCurrency = CryptoCurrency::query()
            ->where('symbol', $request->get('symbol'))
            ->first();

        return view('crypto.trade', [
            'accounts' => $accounts,
            'cryptoCurrency' => $cryptoCurrency,
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->get('search_crypto');
        $cryptoCurrencies = CryptoCurrency::query()
            ->where('symbol', 'like', '%' . strtoupper($search) . '%')
            ->orderBy('price', 'desc')
            ->paginate(15);

        return view('crypto.market', [
            'cryptoCurrencies' => $cryptoCurrencies,
        ]);
    }
}
