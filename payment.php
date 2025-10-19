<?php
// -------------------------
// Primera Living Payment
// -------------------------

// Show errors except notices
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

session_start();

require __DIR__ . '/config/config.php';
require __DIR__ . '/config/CRUD.php';

use Xendit\Invoice\InvoiceApi;
use GuzzleHttp\Client;

// 🔒 Redirect if not logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['login_message'] = "You must log in to make a payment.";
    header("Location: auth/login.php");
    exit();
}

// Get logged-in user info
$user       = $_SESSION['user'];
$user_id    = $user['user_id'];
$tenantEmail = $user['email'] ?? '';
$first_name = $user['first_name'] ?? '';
$last_name  = $user['last_name'] ?? '';
$full_name  = trim("$first_name $last_name");

// Example payment info (replace with actual values from form or session)
$amount      = 25000;            // Example amount
$description = 'Monthly Rent';    // Example description
$invoice_id  = 'rent-' . time();  // Unique invoice ID
$method      = 'Xendit';          // Payment method
$status      = 'Pending';         // Default status

// Optional: property/unit info
$property = $_SESSION['user']['unit_name'] ?? null;

// Initialize Xendit
$client = new Client(['auth' => [$XENDIT_API_KEY, '']]);
$invoiceApi = new InvoiceApi($client);

try {
    // Create invoice via Xendit
    $invoice = $invoiceApi->createInvoice([
        'external_id'        => $invoice_id,
        'payer_email'        => $tenantEmail,
        'description'        => $description,
        'amount'             => $amount,
        'success_redirect_url'=> $config['app']['base_url'] . '/payment-success.php?invoice_id=' . $invoice_id
    ]);

    // Save transaction to DB using CRUD
    $crud = new CRUD($pdo);
    $crud->addTransaction(
        $user_id,
        $tenantEmail,
        $invoice_id,
        $description,
        $amount,
        $status,
        $method
    );

    // Optional: if you want to store property/unit, you can extend addTransaction() to accept it

    // Redirect tenant to Xendit checkout
    header("Location: " . $invoice['invoice_url']);
    exit();

} catch (\Xendit\Exceptions\ApiException $e) {
    include __DIR__ . '/includes/header.php';
    echo '<div class="container my-5">';
    echo '<div class="alert alert-danger text-center">';
    echo 'Error creating invoice: ' . htmlspecialchars($e->getMessage());
    echo '</div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
} catch (Exception $e) {
    include __DIR__ . '/includes/header.php';
    echo '<div class="container my-5">';
    echo '<div class="alert alert-danger text-center">';
    echo 'Unexpected error: ' . htmlspecialchars($e->getMessage());
    echo '</div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
