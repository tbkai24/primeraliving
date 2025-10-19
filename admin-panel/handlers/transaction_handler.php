<?php
require_once '../config/config.php';
require_once '../config/CRUD.php';

$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ==========================
    // READ: Fetch all transactions
    // Optional 'archived' flag: 0 = active, 1 = archived
    // ==========================
    case 'read':
        $archived = $_POST['archived'] ?? 0;
        try {
            $all = $crud->getAllTransactions(); // Fetch all transactions
            if ($archived) {
                $data = array_filter($all, fn($t) => $t['status'] === 'Archived');
            } else {
                $data = array_filter($all, fn($t) => $t['status'] !== 'Archived');
            }

            if (!$data) {
                echo json_encode(['success' => true, 'data' => [], 'message' => 'No transactions found.']);
                exit;
            }

            echo json_encode(['success' => true, 'data' => array_values($data)]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch transactions.']);
        }
        break;

    // ==========================
    // GET: Fetch a single transaction by ID
    // ==========================
    case 'get':
        $id = $_POST['id'] ?? 0;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing transaction ID.']);
            exit;
        }

        try {
            $t = $crud->getTransactionById($id);

            if ($t) {
                echo json_encode(['success' => true, 'data' => $t]);
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
            $result = $crud->archiveTransaction($id); // Use CRUD method

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
