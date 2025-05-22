<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\App;

// Create new App instance
$app = new App('My PHP Project');

// Basic routing
$request = $_SERVER['REQUEST_URI'];

// Simple router
switch ($request) {
    case '/':
        echo $app->sayHello();
        break;
    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
} 