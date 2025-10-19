<?php
$apiKey = "xnd_development_KnCdyrMQKaIo1muKe3wwKImO1538WSKbWlm1iSuxuKJD3tM73Rl296sWU9tTzj"; // Secret API key

$ch = curl_init("https://api.xendit.co/v2/invoices");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":");

$data = [
    "external_id" => uniqid("invoice-"),
    "amount" => 10000,
    "payer_email" => "customer@email.com",
    "description" => "Test Invoice"
];

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result["invoice_url"])) {
    // Redirect user to invoice checkout page
    header("Location: " . $result["invoice_url"]);
    exit;
} else {
    echo "Error creating invoice: ";
    print_r($result);
}
