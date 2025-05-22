<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Controllers\ShippingApiController;

class ShippingApiTest extends TestCase
{
    public function test_calculate_shipping_api_returns_price()
    {
        $controller = new ShippingApiController();
        $input = [
            'origin_cep' => '01001-000',
            'destination_cep' => '20040-000',
            'weight' => 2.0
        ];
        $result = $controller->calculate($input);
        $this->assertArrayHasKey('price', $result);
        $this->assertIsNumeric($result['price']);
    }
} 