<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CryptoCurrency;

class CryptoAPI extends Controller
{
    public function database()
    {
        $url = 'https://api.coinbase.com/v2/exchange-rates?currency=EUR';
        $response = file_get_contents($url);
        $data = json_decode($response, true)['data'];

        foreach ($data['rates'] as $symbol => $price) {
            $cryptoCurrency = CryptoCurrency::where('symbol', $symbol)->first();
            if ($cryptoCurrency) {
                $cryptoCurrency->update([
                    'price' => 1 / $price,
                ]);
                $cryptoCurrency->save();
                continue;
            }
            $cryptoCurrency = (new CryptoCurrency())->fill([
                'symbol' => $symbol,
                'price' => 1 / $price,
            ]);
            $cryptoCurrency->save();
        }
    }

}
