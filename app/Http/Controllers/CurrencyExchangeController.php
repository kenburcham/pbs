<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Values;
use Exception;
use Illuminate\Http\Request;

class CurrencyExchangeController extends Controller
{
    protected $currencyNames;
    public function __construct()
    {
        $this->currencyNames =  array(['code' => "EUR", 'name' => "Euro"], ['code' => "USD", 'name' => "US Dollar"], ['code' => "JPY", 'name' => "Japanese Yen"], ['code' => "BGN", 'name' => "Bulgarian Lev"], ['code' => "CZK", 'name' => "Czech Koruna"], ['code' => "brl", 'name' => "Brazilian real"], ['code' => "btc", 'name' => "Bitcoin"], ['code' => "bzd", 'name' => "Belize dollar"], ['code' => "cad", 'name' => "Canadian dollar"], ['code' => "chf", 'name' => "Swiss franc"],) ;
    }

    public function store(Request $request)
    {
        $currency_date = $request->input('currency_date');
        $currency_code = $request->input('currency_code');
        $amount = $request->input('amount');
        if(empty($currency_date) || empty($currency_code) || empty($amount)) {
            $History = new History();
            $History->currency_date = empty($currency_date) ? null : $currency_date;
            $History->currency_code = empty($currency_code) ? null : $currency_code;
            $History->currency_name = empty($currency_code) ? null : $this->currencyNames[random_int(0,9)]['name'];
            $History->amount = empty($amount) ? null : $amount;
            $History->success = false;
            $History->message = 'All fields are requiered';
            $History->save();
            return response('All fields are requiered', 400);
        }
        $History = new History();
        $History->currency_date = $currency_date;
        $History->currency_code = $currency_code;
        $History->currency_name = $this->currencyNames[random_int(0,9)]['name'];
        $History->amount = $amount;
        $History->save();
        for ($i=0; $i < 10; $i++) {
            $Values = new Values();
            $Values->history_id = $History->id;
            $Values->currency_code = $this->currencyNames[$i]['code'];
            $Values->currency_name = $this->currencyNames[$i]['name'];
            $Values->amount = ($i + 1) * random_int(1, 100);
            $Values->save();
        }
        return response('saved', 200);
    }

    public function show(Request $request)
    {
        $History = History::with(['values'])->orderBy('id', 'desc')->get();
        return response($History, 200);
    }
}
