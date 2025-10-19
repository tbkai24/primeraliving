<?php
require_once '../config/config.php';
require_once '../config/CRUD.php';

$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ==========================
    // READ: Fetch transactions
    // Accepts optional 'archived' flag: 0 = active, 1 = archived
    // ==========================
    case 'read':
        $archived = $_POST['archived'] ?? 0;
        try {
            $query = $archived
                ? "SELECT * FROM transaction_history WHERE status='Archived' ORDER BY created_at DESC"
                : "SELECT * FROM transaction_history WHERE status<>'Archived' ORDER BY created_at DESC";

            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch transactions.']);
        }
        break;

    // ==========================
    // GET: Fetch single transaction by ID
    // ==========================
    case 'get':
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing transaction ID.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM transaction_history WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Transaction not found.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch transaction.']);
        }
        break;

    // ==========================
    // ARCHIVE: Move transaction to Archived
    // ==========================
    case 'archive':
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing transaction ID.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE transaction_history SET status='Archived' WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Transaction archived successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to archive transaction.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error archiving transaction.']);
        }
        break;

    // ==========================
    // INVALID ACTION
    // ==========================
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
