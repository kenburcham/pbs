<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Library\ExchangeInterface;
use App\Models\History;
use App\Models\Values;

class ProcessCurrencyRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $history;
    public $target_currency_name;
    public $target_currency_code;
    public $api;

    /**
     * Create a new job instance.
     * @param History $history
     * @param string $target_currency_code
     * @param string $target_currency_name
     * @param ExchangeInterface $api
     * 
     * @return void
     */
    public function __construct(History $history, string $target_currency_code, string $target_currency_name, ExchangeInterface $api)
    {
        $this->history = $history;
        $this->target_currency_code = $target_currency_code;
        $this->target_currency_name = $target_currency_name;
        $this->api = $api;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //fetch the rate
        $rate = $this->api->getTargetRate(
            $this->history->currency_code, 
            $this->target_currency_code, 
            $this->history->currency_date->format('Y-m-d')
        );
        
        //save value to database
        $Values = new Values();
            $Values->history_id = $this->history->id;
            $Values->currency_code = strtoupper($this->target_currency_code);
            $Values->currency_name = $this->target_currency_name;
            $Values->amount = $rate * $this->history->amount; //multiply whatever amount they gave with our rate
            $Values->save();
    }
}
