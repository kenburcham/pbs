<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateTest extends TestCase
{
    /** @test */
    public function can_start_calculation(){
        $response = $this->post('/api/v1/calculate', [
            'currency_date' => '2021-06-24',
            'currency_code' => 'USD',
            'amount' => '250',
        ]);

        $response->assertStatus(200);
    }
}
