<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ProcessCurrencyRequest;
use App\Library\FawazahmedExchange;
use App\Library\VatcomplyExchange;

class HandleCalculationRequest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //ok so from here we want to send this event to the queue for handling

        //1 - on each api - get 5 random currencies to exchange
        //2 - dispatch requests to our queue job to fetch exchange rates for each currency

        //for each api
        // get 5 random currencies
        //  for each target currency

        $api_exchanges = [ 
            new VatcomplyExchange(),
            new FawazahmedExchange(),
        ];

        foreach($api_exchanges as $api){
            //get 5 random currencies to exchange
            $possible_currencies = $api->getPossibleCurrencies();
            $random_currencies = array_rand($possible_currencies, 5);
    
            //dispatch each of our random 5 currencies (fetch the rate and save) to a queue job
            foreach ($random_currencies as $currency){
                ProcessCurrencyRequest::dispatch($event->history, $currency, $possible_currencies[$currency], $api);
            }

            //set the name of our requested currency
            if(array_key_exists($event->history->currency_code, $possible_currencies)){
                $event->history->currency_name = $possible_currencies[$event->history->currency_code];
            }
        }

        $event->history->success = true;
        $event->history->message = "Success.";
        $event->history->save();
        
    }

    
}
