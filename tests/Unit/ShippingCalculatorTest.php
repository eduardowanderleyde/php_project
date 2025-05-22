<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ShippingCalculator;

class ShippingCalculatorTest extends TestCase
{
    public function test_calculate_shipping_with_valid_data()
    {
        $calculator = new ShippingCalculator();
        $result = $calculator->calculate('01001-000', '20040-000', 2.5); // Exemplo: São Paulo para Rio de Janeiro, 2.5kg
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    public function test_calculate_shipping_with_invalid_origin_cep()
    {
        $calculator = new ShippingCalculator();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid origin CEP');
        $calculator->calculate('000', '20040-000', 2.5);
    }

    public function test_calculate_shipping_with_invalid_destination_cep()
    {
        $calculator = new ShippingCalculator();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid destination CEP');
        $calculator->calculate('01001-000', 'abc', 2.5);
    }

    public function test_calculate_shipping_with_negative_weight()
    {
        $calculator = new ShippingCalculator();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight must be a positive number.');
        $calculator->calculate('01001-000', '20040-000', -1);
    }

    public function test_calculate_shipping_with_api_error()
    {
        $calculator = new ShippingCalculator();
        $calculator->simulateApiError(true);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Shipping API error simulated.');
        $calculator->calculate('01001-000', '20040-000', 2.5);
    }

    public function test_cep_is_normalized()
    {
        $this->assertEquals('01001000', ShippingCalculator::normalizeCep('01001-000'));
        $this->assertEquals('20040000', ShippingCalculator::normalizeCep('20040-000'));
    }

    public function test_calculate_shipping_same_cep()
    {
        $mockClient = $this->createMock(\App\Services\HttpClientInterface::class);
        $mockClient->method('getAddressByCep')->willReturnOnConsecutiveCalls(
            ['localidade' => 'São Paulo', 'uf' => 'SP'],
            ['localidade' => 'São Paulo', 'uf' => 'SP']
        );
        $calculator = new ShippingCalculator($mockClient);
        $result = $calculator->calculate('01001-000', '01001-000', 2);
        $this->assertEquals(20.0, $result); // 10 * 2
    }

    public function test_calculate_shipping_same_city()
    {
        $mockClient = $this->createMock(\App\Services\HttpClientInterface::class);
        $mockClient->method('getAddressByCep')->willReturnOnConsecutiveCalls(
            ['localidade' => 'São Paulo', 'uf' => 'SP'],
            ['localidade' => 'São Paulo', 'uf' => 'SP']
        );
        $calculator = new ShippingCalculator($mockClient);
        $result = $calculator->calculate('01001-000', '01002-000', 3);
        $this->assertEquals(45.0, $result); // 15 * 3
    }

    public function test_calculate_shipping_same_state()
    {
        $mockClient = $this->createMock(\App\Services\HttpClientInterface::class);
        $mockClient->method('getAddressByCep')->willReturnOnConsecutiveCalls(
            ['localidade' => 'Campinas', 'uf' => 'SP'],
            ['localidade' => 'São Paulo', 'uf' => 'SP']
        );
        $calculator = new ShippingCalculator($mockClient);
        $result = $calculator->calculate('13010-000', '01001-000', 1.5);
        $this->assertEquals(30.0, $result); // 20 * 1.5
    }

    public function test_calculate_shipping_different_states()
    {
        $mockClient = $this->createMock(\App\Services\HttpClientInterface::class);
        $mockClient->method('getAddressByCep')->willReturnOnConsecutiveCalls(
            ['localidade' => 'São Paulo', 'uf' => 'SP'],
            ['localidade' => 'Rio de Janeiro', 'uf' => 'RJ']
        );
        $calculator = new ShippingCalculator($mockClient);
        $result = $calculator->calculate('01001-000', '20040-000', 2);
        $this->assertEquals(70.0, $result); // 35 * 2
    }
} 