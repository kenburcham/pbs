<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Values;
use Exception;
use Illuminate\Http\Request;
use App\Events\NewCalculationRequestReceivedEvent;

class CurrencyExchangeController extends Controller
{
    protected $currencyNames;
    public function __construct()
    {
        $this->currencyNames =  array(['code' => "EUR", 'name' => "Euro"], ['code' => "USD", 'name' => "US Dollar"], ['code' => "JPY", 'name' => "Japanese Yen"], ['code' => "BGN", 'name' => "Bulgarian Lev"], ['code' => "CZK", 'name' => "Czech Koruna"], ['code' => "brl", 'name' => "Brazilian real"], ['code' => "btc", 'name' => "Bitcoin"], ['code' => "bzd", 'name' => "Belize dollar"], ['code' => "cad", 'name' => "Canadian dollar"], ['code' => "chf", 'name' => "Swiss franc"],) ;
    }

    public function store(Request $request)
    {
        //incoming fields
        $currency_date = $request->input('currency_date');
        $currency_code = $request->input('currency_code');
        $amount = $request->input('amount');

        //check if any are empty - save a record and return an error if so.
        if(empty($currency_date) || empty($currency_code) || empty($amount)) {
            $History = new History();
            $History->currency_date = empty($currency_date) ? null : $currency_date;
            $History->currency_code = empty($currency_code) ? null : $currency_code;
            $History->currency_name = empty($currency_code) ? null : $this->currencyNames[random_int(0,9)]['name'];
            $History->amount = empty($amount) ? null : $amount;
            $History->success = false;
            $History->message = 'All fields are required';
            $History->save();
            return response('All fields are required', 400);
        }

        
        //we have a valid request, so lets save the request and fire an event to queue it
        $History = new History();
        $History->currency_date = $currency_date;
        $History->currency_code = $currency_code;
        $History->currency_name = $this->currencyNames[random_int(0,9)]['name'];
        $History->amount = $amount;
        $History->success = false; //indicates it is not yet processed by the queue
        $History->save();
        
        NewCalculationRequestReceivedEvent::dispatch($History);

        //we'll do this in the event handler
        // for ($i=0; $i < 10; $i++) {
        //     $Values = new Values();
        //     $Values->history_id = $History->id;
        //     $Values->currency_code = $this->currencyNames[$i]['code'];
        //     $Values->currency_name = $this->currencyNames[$i]['name'];
        //     $Values->amount = ($i + 1) * random_int(1, 100);
        //     $Values->save();
        // }
        return response('saved', 200);
    }

    public function show(Request $request)
    {
        $History = History::with(['values'])->orderBy('id', 'desc')->get();
        return response($History, 200);
    }
}
