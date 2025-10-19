<?php
session_start();

// ---------------- DEBUGGING (remove in production) ----------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------------- FORCE JSON OUTPUT ----------------
header('Content-Type: application/json; charset=utf-8');

// ---------------- INCLUDES ----------------
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/CRUD.php';

// ---------------- AUTH CHECK ----------------
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must log in.']);
    exit;
}

// ---------------- INITIAL SETUP ----------------
$crud = new CRUD($pdo);
$userId = $_SESSION['user']['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Invalid request.'];

// ---------------- MAIN LOGIC ----------------
try {
    switch ($action) {

        /* ---------------- PROFILE ---------------- */
        case 'update_profile':
            $first = trim($_POST['first_name'] ?? '');
            $last = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');

            if ($crud->updateUserProfile($userId, $first, $last, $email, $mobile)) {
                $_SESSION['user'] = array_merge($_SESSION['user'], [
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $email,
                    'mobile' => $mobile
                ]);
                $response = ['status' => 'success', 'message' => 'Profile updated successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Database update failed.'];
            }
            break;

        /* ---------------- PASSWORD ---------------- */
        case 'update_password':
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $user = $crud->getUserById($userId);
            if (!$user) throw new Exception('User not found.');

            if (!password_verify($current, $user['password'])) {
                $response = ['status' => 'error', 'message' => 'Current password is incorrect.'];
            } elseif ($new !== $confirm) {
                $response = ['status' => 'error', 'message' => 'New passwords do not match.'];
            } else {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                if ($crud->updateUserPassword($userId, $hashed)) {
                    $response = ['status' => 'success', 'message' => 'Password updated successfully.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Unable to update password.'];
                }
            }
            break;

        /* ---------------- 2FA ---------------- */
        case 'toggle_2fa':
            $enabled = isset($_POST['enable_2fa']) && $_POST['enable_2fa'] == 1 ? 1 : 0;
            if ($crud->updateUser2FA($userId, $enabled)) {
                $_SESSION['user']['two_factor_enabled'] = $enabled;
                $response = ['status' => 'success', 'message' => '2FA setting updated.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Unable to update 2FA setting.'];
            }
            break;

        /* ---------------- BILLING ---------------- */
        case 'update_billing':
            $billingEmail = trim($_POST['billing_email'] ?? '');
            $billingMethod = trim($_POST['payment_method'] ?? '');

            if ($crud->updateUserBilling($userId, $billingEmail, $billingMethod)) {
                $_SESSION['user']['billing_email'] = $billingEmail;
                $_SESSION['user']['billing_method'] = $billingMethod;
                $response = ['status' => 'success', 'message' => 'Billing info updated successfully.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Unable to update billing info.'];
            }
            break;

        /* ---------------- NOTIFICATIONS ---------------- */
        case 'update_notifications':
            $emailNotif = isset($_POST['email_notifications']) && $_POST['email_notifications'] == 1 ? 1 : 0;
            if ($crud->updateUserNotification($userId, $emailNotif)) {
                $_SESSION['user']['email_notifications'] = $emailNotif;
                $response = ['status' => 'success', 'message' => 'Notification preferences updated.'];
            } else {
                $response = ['status' => 'error', 'message' => 'Unable to update notifications.'];
            }
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Unknown action.'];
            break;
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()];
}

// ---------------- RETURN JSON ----------------
echo json_encode($response);
exit;
