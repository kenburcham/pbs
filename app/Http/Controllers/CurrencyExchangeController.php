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
        //we'll populate the name later from the api's currency list
        //$this->currencyNames =  array(['code' => "EUR", 'name' => "Euro"], ['code' => "USD", 'name' => "US Dollar"], ['code' => "JPY", 'name' => "Japanese Yen"], ['code' => "BGN", 'name' => "Bulgarian Lev"], ['code' => "CZK", 'name' => "Czech Koruna"], ['code' => "brl", 'name' => "Brazilian real"], ['code' => "btc", 'name' => "Bitcoin"], ['code' => "bzd", 'name' => "Belize dollar"], ['code' => "cad", 'name' => "Canadian dollar"], ['code' => "chf", 'name' => "Swiss franc"],) ;
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
            $History->currency_name = ''; 
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
        $History->currency_name = ''; //we'll populate this later from the full api list
        $History->amount = $amount;
        $History->success = false; //indicates it is not yet processed by the queue
        $History->save();
        
        //dispatch event to our HandleCalculationRequest listener
        NewCalculationRequestReceivedEvent::dispatch($History);

        return response('saved', 200);
    }

    public function show(Request $request)
    {
        $History = History::with(['values'])->orderBy('id', 'desc')->get();
        return response($History, 200);
    }
}
