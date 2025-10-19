<?php
// ===============================
// Database Configuration
// ===============================
$config['db_dsn']  = "mysql:host=localhost;dbname=primeraliving;charset=utf8mb4";
$config['db_user'] = "root";   // Replace with your DB username
$config['db_pass'] = "";       // Replace with your DB password

// Optional: global PDO connection
try {
    $pdo = new PDO(
        $config['db_dsn'],
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ===============================
// SMTP Configuration
// ===============================
$config['smtp'] = [
    'host'   => 'smtp.gmail.com',
    'user'   => 'primeralivingph@gmail.com',
    'pass'   => 'cjaohkrscwktspzz',
    'port'   => 587,
    'secure' => 'tls',
];
