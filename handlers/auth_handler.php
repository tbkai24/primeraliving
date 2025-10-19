<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); // suppress notices/warnings that break JSON

include '../config/config.php';
include '../config/CRUD.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$crud   = new CRUD($pdo);

// ------------------ Helpers ------------------
function jsonResponse($success, $msg, $require_verification = false) {
    echo json_encode([
        'success' => $success,
        'message' => $msg,
        'require_verification' => $require_verification
    ]);
    exit;
}

function getFullName($first, $last) {
    return trim($first . ' ' . $last);
}

function sendVerificationEmail($toEmail, $toName, $code, $config) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $config['smtp']['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp']['user'];
    $mail->Password   = $config['smtp']['pass'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $config['smtp']['port'];

    $mail->setFrom($config['smtp']['user'], 'Primera Living');
    $mail->addAddress($toEmail, $toName);
    $mail->isHTML(true);
    $mail->Subject = 'Primera Living: Confirm Your Account';
    $mail->Body = "
        <div style='font-family: Arial, sans-serif; line-height: 1.5;'>
            <h2 style='color:#2A6F9E;'>Welcome to Primera Living, {$toName}!</h2>
            <p>You're just one step away from accessing your account.</p>
            <p style='font-size: 18px;'>Your verification code is:</p>
            <p style='font-size: 24px; font-weight: bold; color:#7e57c2;'>{$code}</p>
            <p>This code will expire in <strong>5 minutes</strong>.</p>
            <hr style='border:none; border-top:1px solid #ccc;'/>
            <p style='font-size:12px; color:#666;'>If you didn't request this, please ignore this email.</p>
            <p style='font-size:12px; color:#666;'>Primera Living &copy; 2025</p>
        </div>
    ";
    $mail->send();
}

// ==========================================================
// REGISTER
// ==========================================================
if ($action === 'register') {
    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$first || !$last || !$email || !$password) {
        jsonResponse(false, 'All fields are required.');
    }

    if ($crud->getUserByEmail($email)) {
        jsonResponse(false, 'Email already registered.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $verificationCode = random_int(100000, 999999);
    $vericodeHash = hash('sha256', $verificationCode);
    $vericodeExpires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    $userId = $crud->createUser($first, $last, $email, $hashedPassword, $vericodeHash, $vericodeExpires);
    if (!$userId) {
        jsonResponse(false, 'Registration failed. Try again.');
    }

    $_SESSION['email'] = $email;

    try {
        sendVerificationEmail($email, getFullName($first, $last), $verificationCode, $config);
        jsonResponse(true, 'Verification code sent. Enter it to complete registration.', true);
    } catch (Exception $e) {
        jsonResponse(false, 'Mailer error: '.$e->getMessage(), true);
    }
}

// ==========================================================
// LOGIN
// ==========================================================
if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        jsonResponse(false, 'Email and password are required.');
    }

    $user = $crud->getUserByEmail($email);
    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(false, 'Invalid email or password.');
    }

    if ($user['verified'] == 0) {
        $_SESSION['email'] = $email;
        $verificationCode = random_int(100000, 999999);
        $vericodeHash = hash('sha256', $verificationCode);
        $vericodeExpires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $crud->setVerificationCode($email, $vericodeHash, $vericodeExpires, true);

        try {
            sendVerificationEmail($email, getFullName($user['first_name'], $user['last_name']), $verificationCode, $config);
            jsonResponse(true, 'Please verify your email. Code sent.', true);
        } catch (Exception $e) {
            jsonResponse(false, 'Mailer error: '.$e->getMessage(), true);
        }
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user'] = [
        'user_id' => $user['user_id'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'mobile_number' => $user['mobile_number']
    ];

    jsonResponse(true, 'Login successful!', false);
}

// ==========================================================
// VERIFY
// ==========================================================
if ($action === 'verify') {
    $vericode_input = trim($_POST['vericode'] ?? '');
    $email = $_SESSION['email'] ?? '';

    if (!$email) jsonResponse(false, 'Session expired.');

    $user = $crud->getUnverifiedUserByEmail($email);
    if (!$user) jsonResponse(false, 'User not found or already verified.');

    if (strtotime($user['vericode_expires']) < time()) {
        jsonResponse(false, 'Code expired. Please resend.', true);
    }

    if (hash('sha256', $vericode_input) === $user['vericode_hash']) {
        $crud->markUserVerified($user['user_id']);
        unset($_SESSION['email']);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'mobile_number' => $user['mobile_number']
        ];

        jsonResponse(true, 'Verification successful!');
    } else {
        jsonResponse(false, 'Invalid verification code.', true);
    }
}

// ==========================================================
// RESEND CODE
// ==========================================================
if ($action === 'resend') {
    $email = $_SESSION['email'] ?? '';
    if (!$email) jsonResponse(false, 'Session expired.', true);

    $user = $crud->getUnverifiedUserByEmail($email);
    if (!$user) jsonResponse(false, 'User not found or already verified.', true);

    $verificationCode = random_int(100000, 999999);
    $vericodeHash = hash('sha256', $verificationCode);
    $vericodeExpires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    $crud->setVerificationCode($email, $vericodeHash, $vericodeExpires, true);

    try {
        sendVerificationEmail($email, getFullName($user['first_name'], $user['last_name']), $verificationCode, $config);
        jsonResponse(true, 'Verification code resent.', true);
    } catch (Exception $e) {
        jsonResponse(false, 'Mailer error: '.$e->getMessage(), true);
    }
}

// ==========================================================
// DEFAULT
// ==========================================================
jsonResponse(false, 'Invalid request.');
