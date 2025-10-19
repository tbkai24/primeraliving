<?php
/*
|----------------------------------------------------------------------
| Config - Primera Living
|----------------------------------------------------------------------
*/

// -------------------------------------------------------------
// 1. Composer Autoload
// -------------------------------------------------------------
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Composer autoload.php not found. Run 'composer install' first.");
}
require $autoloadPath;

// -------------------------------------------------------------
// 2. Database Config (PDO)
// -------------------------------------------------------------

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$config['db'] = [
    'host' => 'localhost',
    'dbname' => 'primeraliving',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
];

try {
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}",
        $config['db']['user'],
        $config['db']['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// -------------------------------------------------------------
// 3. SMTP Config
// -------------------------------------------------------------
$config['smtp'] = [
    'host' => 'smtp.gmail.com',
    'user' => 'primeralivingph@gmail.com',
    'pass' => 'cjaohkrscwktspzz',
    'port' => 587,
    'secure' => 'tls',
];

// -------------------------------------------------------------
// 4. Xendit Config (SDK v4+)
// -------------------------------------------------------------
use Xendit\Invoice;

$config['xendit'] = [
    'mode' => 'development', // 'development' or 'production'
    'development' => [
        'api_key' => 'xnd_development_GrpEBTWY4ddHO4gqQrQkuYRtANDfwthbWITtlktb6Yhe3zoY6Fq6FblNzcDYL9y',
    ],
    'production' => [
        'api_key' => 'xnd_live_YOUR_LIVE_KEY_HERE',
    ]
];

$XENDIT_API_KEY = $config['xendit'][$config['xendit']['mode']]['api_key'];

// -------------------------------------------------------------
// 5. Misc / App Settings
// -------------------------------------------------------------
$config['app'] = [
    'base_url' => 'https://localhost/primeraliving',
];

// -------------------------------------------------------------
// 6. reCAPTCHA Config
// -------------------------------------------------------------
$config['recaptcha'] = [
    'site' => '6Ld44MkrAAAAALqoLiZxCjLb451NX3FyrUXkVPsl',   // Site key (public)
    'secret' => '6Ld44MkrAAAAAO0ncO7VZHQ0gNVyLZZKD0srk6Oa', // Secret key (private)
];
?>
