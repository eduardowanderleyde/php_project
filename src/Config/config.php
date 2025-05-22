<?php

return [
    'app' => [
        'name' => 'Mini CMS',
        'url' => 'http://localhost:8000',
        'debug' => true,
    ],
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'mini_cms',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],
    'admin' => [
        'email' => $_ENV['ADMIN_EMAIL'] ?? 'admin@example.com',
        'items_per_page' => 10,
    ],
    'upload' => [
        'max_size' => 5242880, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
        'path' => __DIR__ . '/../../public/uploads/',
    ]
]; 