<?php
require __DIR__ . '/../vendor/autoload.php';
$gmconfig = require __DIR__ . '/../config/gmconfig.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $gmconfig['GMAIL_USERNAME'];
    $mail->Password = $gmconfig['GMAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($gmconfig['GMAIL_USERNAME'], 'Primera Living');
    $mail->addAddress('kennstiago@gmail.com'); // test recipient
    $mail->Subject = 'Test Email';
    $mail->Body    = 'Hello! This is a test email from PHPMailer via Composer.';

    $mail->send();
    echo "✅ Email sent successfully!";
} catch (Exception $e) {
    echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
}
