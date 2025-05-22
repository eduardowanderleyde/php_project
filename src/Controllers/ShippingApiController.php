<?php

namespace App\Controllers;

use App\Services\ShippingCalculator;
use App\Services\ApiFreightClient;
use Exception;

class ShippingApiController
{
    public function calculate(array $input): array
    {
        $originCep = $input['origin_cep'] ?? null;
        $destinationCep = $input['destination_cep'] ?? null;
        $weight = $input['weight'] ?? null;

        if (!$originCep || !$destinationCep || !$weight) {
            throw new Exception('Missing required fields: origin_cep, destination_cep, weight');
        }
        $client = new ApiFreightClient();
        $calculator = new ShippingCalculator($client);
        $price = $calculator->calculate($originCep, $destinationCep, (float)$weight);
        return ['price' => $price];
    }
} 