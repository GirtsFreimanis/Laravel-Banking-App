<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyAPI extends Controller
{
    public const BASE_URL = 'http://api.exchangeratesapi.io/latest?access_key=';

    public function fillDatabase()
    {
        $url = self::BASE_URL . 'd4ec03816227748dd6305bd86309cc74';
        $data = json_decode(file_get_contents($url), true);

        $ignoreCurrencies = [
            'BTC',
            'XAU',
            'XAG',
            'CLF',
        ];

        foreach ($data["rates"] as $symbol => $rate) {
            if (in_array($symbol, $ignoreCurrencies)) {
                continue;
            }

            $currency = Currency::where('symbol', $symbol)->first();
            if ($currency) {
                $currency->update([
                    'rate' => $rate * 100,
                ]);
                $currency->save();
                continue;
            }
            $currency = new Currency([
                'symbol' => $symbol,
                'rate' => $rate * 100,
            ]);
            $currency->save();
        }
    }
}
