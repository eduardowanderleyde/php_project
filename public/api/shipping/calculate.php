<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Services\ShippingCalculator;
use App\Services\ApiFreightClient;

header('Content-Type: application/json');

function calculate_shipping_api($input) {
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

if (php_sapi_name() !== 'cli') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    try {
        $result = calculate_shipping_api($input);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} 