<?php

namespace App\Library;

use App\Library\ExchangeInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class FawazahmedExchange implements ExchangeInterface {

    private $api_rates = "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1"; //todo: get this from services.php
    private $api_currencies = "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json";
    //https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/2020-11-24/currencies/usd.json

    //https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/2020-11-24/currencies/eur/jpy.json

    //https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/eur/jpy.json

    public function getPossibleCurrencies() {
        $response = Http::get($this->api_currencies);
        $currencies = $response->json();
        return $currencies;
    }

    public function getRates($currency_base, $currency_date) {
        $base = strtolower($currency_base);
        //pattern = https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/2020-11-24/currencies/usd.json
        $url = $this->api_rates . '/'. $currency_date . '/currencies/' . $base . '.json';
        $response = Http::get($url);
        $rates = $response->json()[$base];
        return $rates;
    }

    //returns string value of rate
    public function getTargetRate($currency_base, $currency_target, $currency_date) {
        $base = strtolower($currency_base);
        $target = strtolower($currency_target);
        //pattern = https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/2020-11-24/currencies/eur/jpy.json
        $url = $this->api_rates . '/'. $currency_date . '/currencies/' .$base . '/'. $target . '.json';
        $response = Http::get($url);
        $rate = $response->json()[$target];
        return $rate;
    }
}