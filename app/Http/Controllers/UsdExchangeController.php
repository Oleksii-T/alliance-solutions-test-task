<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsdExchange;

class UsdExchangeController extends Controller
{
    static public function update() {
        // get the api key
        $key = env('EXCHANGERATES_APP_ID');
        if (!$key) {
            return false;
        }

        // read url as json
        $url = "https://openexchangerates.org/api/latest.json?app_id=".$key;
        $json = file_get_contents($url);

        //read json and retrive rates
        $eurRate = json_decode($json)->rates->EUR;
        $uahRate = json_decode($json)->rates->UAH;
        $rubRate = json_decode($json)->rates->RUB;

        //update rates in databalse
        UsdExchange::where('currency', 'UAH')->update(['sell' => $uahRate]);
        UsdExchange::where('currency', 'EUR')->update(['sell' => $eurRate]);
        UsdExchange::where('currency', 'RUB')->update(['sell' => $rubRate]);
        return true;
    }

    static public function convertToUsd($amount, $currency) {
        return  round( $amount / UsdExchange::where('currency', $currency)->first()->sell, 2 );
    }

    static public function uahToUsd($uah) {
        return  round( $uah / UsdExchange::findOrFail(1)->sell, 2 );
    }
}
