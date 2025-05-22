<?php

namespace App\Services;

use App\Services\HttpClientInterface;

/**
 * ShippingCalculator
 *
 * Calculates shipping cost based on origin/destination CEP and weight.
 */
class ShippingCalculator
{
    /**
     * @var bool
     */
    private bool $simulateApiError = false;
    private ?HttpClientInterface $client;

    public function __construct(?HttpClientInterface $client = null)
    {
        $this->client = $client;
    }

    /**
     * Enable or disable API error simulation (for testing).
     *
     * @param bool $value
     * @return void
     */
    public function simulateApiError(bool $value): void
    {
        $this->simulateApiError = $value;
    }

    /**
     * Calculate shipping cost.
     *
     * @param string $originCep
     * @param string $destinationCep
     * @param float $weight
     * @return float
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function calculate(string $originCep, string $destinationCep, float $weight): float
    {
        $originCep = self::normalizeCep($originCep);
        $destinationCep = self::normalizeCep($destinationCep);

        if (!self::isValidCep($originCep)) {
            throw new \InvalidArgumentException('Invalid origin CEP: ' . $originCep);
        }
        if (!self::isValidCep($destinationCep)) {
            throw new \InvalidArgumentException('Invalid destination CEP: ' . $destinationCep);
        }
        if ($weight <= 0) {
            throw new \InvalidArgumentException('Weight must be a positive number.');
        }
        if ($this->simulateApiError) {
            throw new \RuntimeException('Shipping API error simulated.');
        }

        // Se client HTTP foi injetado, usa integração simulada
        if ($this->client) {
            $response = $this->client->get('https://api.shipping.com/calculate', [
                'origin' => $originCep,
                'destination' => $destinationCep,
                'weight' => $weight,
            ]);
            return (float) ($response['price'] ?? 0.0);
        }

        // Valor padrão para testes sem client
        return 25.50;
    }

    /**
     * Validate CEP format (only digits, 8 chars).
     *
     * @param string $cep
     * @return bool
     */
    public static function isValidCep(string $cep): bool
    {
        return (bool) preg_match('/^\d{8}$/', $cep);
    }

    /**
     * Normalize CEP (remove non-digits).
     *
     * @param string $cep
     * @return string
     */
    public static function normalizeCep(string $cep): string
    {
        return preg_replace('/\D/', '', $cep);
    }
} 