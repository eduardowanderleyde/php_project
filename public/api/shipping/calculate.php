<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Controllers\ShippingApiController;

header('Content-Type: application/json');

if (php_sapi_name() !== 'cli') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    try {
        $controller = new ShippingApiController();
        $result = $controller->calculate($input);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
} 