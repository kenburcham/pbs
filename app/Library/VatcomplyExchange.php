<?php

namespace App\Library;

use App\Library\ExchangeInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class VatcomplyExchange implements ExchangeInterface {

    private $api_rates = "https://api.vatcomply.com/rates"; //todo: get this from services.php
    private $api_currencies = "https://api.vatcomply.com/currencies";

    //specific rate:
    //https://api.vatcomply.com/rates?date=2000-04-05&base=USD&symbols=JPY
    //returns: {"date":"2000-04-05","base":"USD","rates":{"JPY":105.14835108032668}}


    //returns array of ["USD":"US Dollars",...]
    public function getPossibleCurrencies() {
        $response = Http::get($this->api_currencies);
        $currencies = $response->json();

        $flattened_currencies = [];

        foreach(array_keys($currencies) as $currency){
            $flattened_currencies[$currency] = $currencies[$currency]['name'];
        }

        return $flattened_currencies;
    }

    public function getRates($currency_base, $currency_date) {
        //not implemented
    }

    //returns string value of rate
    public function getTargetRate($currency_base, $currency_target, $currency_date) {
        $base = strtoupper($currency_base);
        $target = strtoupper($currency_target);
        //pattern = https://api.vatcomply.com/rates?date=2000-04-05&base=USD&symbols=JPY
        $url = $this->api_rates . 
            '?date='. $currency_date . 
            '&base=' .$base . 
            '&symbols='. $target;
        $response = Http::get($url);
        return $response->json()['rates'][$target];
    }
}