<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Library\FawazahmedExchange;
use App\Library\VatcomplyExchange;

class CalculateTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();
        //Event::fake();
    }

    /** @test */
    public function can_run_calculation(){
        $this->withoutExceptionHandling();
        $response = $this->post('/api/v1/calculate', [
            'currency_date' => '2021-06-24',
            'currency_code' => 'USD',
            'amount' => '250',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function missing_fields_return_error(){
        $response = $this->post('/api/v1/calculate', [
            'currency_date' => '2021-06-24',
            'currency_code' => '',
            'amount' => '250',
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function fawazahmed_can_get_rates(){
        $api = new FawazahmedExchange();
        //$possible_currencies = $api->getPossibleCurrencies();
        //$api_currencies = [];

        $rates = $api->getRates('USD', '2021-06-01');

        $this->assertIsArray($rates);

    }

    /** @test */
    public function fawazahmed_can_get_single_rate(){
        $api = new FawazahmedExchange();

        $rate = $api->getTargetRate('USD', 'JPY', '2021-06-01');

        $this->assertIsNumeric($rate);
        $this->assertEquals($rate, 109.401); //and actually we know it exactly!
    }

    /** @test */
    public function vatcomply_can_get_currencies(){
        $api = new VatcomplyExchange();
        $currencies = $api->getPossibleCurrencies();

        $this->assertIsArray($currencies);
    }

    /** @test */
    public function vatcomply_can_get_single_rate(){

        $this->withoutExceptionHandling();

        $api = new VatcomplyExchange();
        $rate = $api->getTargetRate('USD','JPY','2021-06-01');

        $this->assertIsNumeric($rate);
        $this->assertEquals($rate, 109.65235173824);
    }

}
