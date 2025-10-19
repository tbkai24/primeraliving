<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start();
require_once '../config/config.php';
require_once '../config/CRUD.php';

$crud = new CRUD($pdo);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

switch ($action) {
    /* ================= FETCH USER TICKETS ================= */
    case 'fetch':
        try {
            $tickets = $crud->getUserTickets($user_id);
            echo json_encode($tickets ?: []);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    /* ================= CREATE NEW TICKET ================= */
    case 'create':
        $category = $_POST['category'] ?? 'Other';
        $subject  = trim($_POST['subject'] ?? '');
        $message  = trim($_POST['message'] ?? '');

        if (empty($subject) || empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Please fill in all required fields.']);
            exit;
        }

        // Generate numeric ticket number (6–8 digits)
        $ticket_number = 'TKT-' . mt_rand(100000, 99999999);

        try {
            // Insert ticket manually since addTicket() doesn’t handle ticket_number
            $stmt = $pdo->prepare("
                INSERT INTO tickets (user_id, ticket_number, category, subject, message, status, created_at)
                VALUES (?, ?, ?, ?, ?, 'Pending', NOW())
            ");
            $ok = $stmt->execute([$user_id, $ticket_number, $category, $subject, $message]);

            echo json_encode([
                'success' => $ok,
                'ticket_number' => $ok ? $ticket_number : null,
                'error' => $ok ? null : 'Failed to create ticket.'
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
