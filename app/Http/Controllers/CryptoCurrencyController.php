<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\CryptoAPI;
use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use App\Models\Investment;
use App\Models\InvestmentHistory;
use Illuminate\Http\Request;

class CryptoCurrencyController extends Controller
{
    public function market()
    {
        $cryptoCurrencies = CryptoCurrency::query()->orderBy('price', 'desc')->Paginate(15);
        return view('crypto.market', [
            'cryptoCurrencies' => $cryptoCurrencies,
        ]);
    }

    public function buyForm(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        $crypto = CryptoCurrency::query()
            ->where('symbol', $request->get('symbol'))
            ->first();

        foreach ($accounts as $account) {
            $account->balance /= 100;
            $accountCurrencyRate = Currency::query()
                ->where('symbol', $account->currency)
                ->first()
                ->rate;

            $account->cryptoPrice = $accountCurrencyRate / 100 * $crypto->price;
            $account->cryptoExchangeRate = number_format(1 / $account->cryptoPrice / 100, 12, ".", "");
            $account->minimumPurchase = $accountCurrencyRate / 10;
        }

        return view('crypto.buy-crypto', [
            'accounts' => $accounts,
            'crypto' => $crypto,
        ]);
    }

    public function store(Request $request)
    {
        $account = Account::query()
            ->where('IBAN', $request->get('account_from'))
            ->where('user_id', $request->user()->id)
            ->first();

        $request->validate([
            'account_from' => 'required',
            'price' => 'required|numeric|min:0|max:' . $account->balance / 100,
        ]);

        $accountCurrencyRate = Currency::query()
            ->where('symbol', $account->currency)
            ->first()
            ->rate;

        $crypto = CryptoCurrency::query()
            ->where('symbol', $request->get('symbol'))
            ->first();

        $cryptoPrice = number_format($accountCurrencyRate / 100 * $crypto->price, 12, ".", "");
        $amount = number_format(1 / $cryptoPrice * $request->get('price') / 100, 12, ".", "");

        $investmentHistory = (new InvestmentHistory())->fill([
            'account_id' => $account->id,
            'crypto_id' => $crypto->id,
            'amount' => $amount,
            'bought_at' => $cryptoPrice,
            'status' => 'bought'
        ]);
        $investmentHistory->save();

        $investment = Investment::query()
            ->where('account_id', $account->id)
            ->where('crypto_id', $crypto->id)
            ->first();

        if (empty($investment)) {
            $newInvestment = (new Investment())->fill([
                'account_id' => $account->id,
                'crypto_id' => $crypto->id,
                'amount' => $amount,
            ]);
            $newInvestment->save();
        } else {
            $investment->update([
                'amount' => $investment->amount + (int)$amount,
            ]);
        }

        $account->update([
            'balance' => $account->balance - $request->get('price') * 100
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaction successful.');
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

    public function portfolio(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        return view('crypto.portfolio', [
            'accounts' => $accounts,
        ]);
    }

    public function portfolioSearch(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        $investments = Investment::query()
            ->join('accounts', 'investments.account_id', '=', 'accounts.id')
            ->join('crypto_currencies', 'investments.crypto_id', '=', 'crypto_currencies.id')
            ->select('crypto_currencies.symbol', 'crypto_currencies.price', 'investments.amount')
            ->where('accounts.user_id', $request->user()->id)
            ->where('accounts.IBAN', $request->get('search_account'))
            ->where('investments.amount', '>', 0)
            ->get();

        return view('crypto.portfolio', [
            'accounts' => $accounts,
            'investments' => $investments,
        ]);
    }

    public function sellForm(Request $request)
    {

        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        return view('crypto.sell-crypto', [
            'accounts' => $accounts,
        ]);
    }

    public function showHoldings(Request $request)
    {
        $account = Account::query()
            ->where('IBAN', $request->get('search_account'))
            ->where('user_id', $request->user()->id)
            ->first();

        $accountCurrencyRate = Currency::query()
            ->where('symbol', $account->currency)
            ->first()
            ->rate;

        $investments = InvestmentHistory::query()
            ->join('accounts', 'investments_history.account_id', '=', 'accounts.id')
            ->join('crypto_currencies', 'investments_history.crypto_id', '=', 'crypto_currencies.id')
            ->where('investments_history.status', 'bought')
            ->where('accounts.user_id', $request->user()->id)
            ->where('accounts.IBAN', $request->get('search_account'))
            ->where('investments_history.amount', '>', 0)
            ->select(
                'crypto_currencies.symbol',
                'crypto_currencies.price',
                'investments_history.amount',
                'investments_history.bought_at',
                'investments_history.id',
                'accounts.IBAN',
            )
            ->get();


        foreach ($investments as $investment) {
            $investment->price *= $accountCurrencyRate / 100;
            $investment->value = number_format($investment->amount * $investment->price * 100, 2, ".", "");
            $investment->gain = number_format((($investment->price - $investment->bought_at) / $investment->bought_at) * 100, 2, ".", "");
        }

        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->where('type', 'investment')
            ->get();

        return view('crypto.sell-crypto', [
            'accounts' => $accounts,
            'investments' => $investments,
        ]);
    }

    public function sell(Request $request)
    {
        $investmentHistory = InvestmentHistory::query()
            ->where('id', $request->investment)
            ->first();

        $account = Account::query()
            ->where('id', $investmentHistory->account_id)
            ->first();

        $crypto = CryptoCurrency::query()
            ->where('id', $investmentHistory->crypto_id)
            ->first();

        $investment = Investment::query()
            ->where('account_id', $account->id)
            ->where('crypto_id', $crypto->id)
            ->first();

        $investment->update([
            'amount' => $investment->amount - $investmentHistory->amount,
        ]);
        $currencyRate = Currency::query()
            ->where('symbol', $account->currency)
            ->first()
            ->rate;

        $value = (int)number_format($investmentHistory->amount * $crypto->price * $currencyRate, 2, ".", "") * 100;

        $account->update([
            'balance' => $account->balance + $value,
        ]);

        $investmentHistory->update([
            'status' => 'sold'
        ]);
        return redirect()->route('dashboard')->with('success', 'Transaction successful.');
    }
}
