<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class ShippingApiTest extends TestCase
{
    public function test_calculate_shipping_api_returns_price()
    {
        require_once __DIR__ . '/../../public/api/shipping/calculate.php';

        $input = [
            'origin_cep' => '01001-000',
            'destination_cep' => '20040-000',
            'weight' => 2.0
        ];

        $result = calculate_shipping_api($input);
        $this->assertArrayHasKey('price', $result);
        $this->assertIsNumeric($result['price']);
    }
} 