<?php
namespace App\Library;

interface ExchangeInterface
{
    public function getPossibleCurrencies();
    public function getRates($currency_base, $currency_date);
    public function getTargetRate($currency_base, $currency_target, $currency_date);
}