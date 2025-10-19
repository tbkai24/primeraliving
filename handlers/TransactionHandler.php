<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

session_start();
require_once '../../config/config.php';
require_once '../../config/CRUD.php';

$crud = new CRUD($pdo);

$action = $_POST['action'] ?? '';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

switch ($action) {
    case 'fetch':
        $tickets = $crud->getUserTickets($user_id);
        echo json_encode($tickets);
        break;

    case 'create':
        $subject  = $_POST['subject'] ?? '';
        $message  = $_POST['message'] ?? '';
        $category = $_POST['category'] ?? 'Other';
        $ticket_number = 'TKT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // Insert manually to include ticket_number
        $stmt = $pdo->prepare("
            INSERT INTO tickets (user_id, ticket_number, category, subject, message, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'Pending', NOW())
        ");
        $ok = $stmt->execute([$user_id, $ticket_number, $category, $subject, $message]);

        echo json_encode([
            'success' => $ok,
            'ticket_number' => $ok ? $ticket_number : null,
            'error' => $ok ? null : 'Failed to create ticket'
        ]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
