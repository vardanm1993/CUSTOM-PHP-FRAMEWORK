<?php

return [
    'database' => [
        'mysql' => [
            'host' => $_ENV['MYSQL_HOST'] ?? 'localhost',
            'port' => $_ENV['MYSQL_PORT'] ?? '3306',
            'dbname' => $_ENV['MYSQL_DATABASE'] ?? 'framework',
            'charset' => $_ENV['MYSQL_CHAR'] ?? 'utf8mb4',
            'user' => $_ENV['MYSQL_USER'] ?? 'root',
            'password' => $_ENV['MYSQL_PASSWORD'] ?? '',
        ]
    ]
];