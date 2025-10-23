<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../config/CRUD.php';
include __DIR__ . '/../config/config.php'; // your PDO $pdo

$crud = new CRUD($pdo);

// 🔒 User must be logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user = $_SESSION['user'];

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ================= GET USER TICKETS =================
    case 'getUserTickets':
        $tickets = $crud->getUserTickets($user['user_id']);
        if(!$tickets) $tickets = [];
        echo json_encode($tickets);
        break;

    // ================= GET TICKET DETAILS + MESSAGES =================
    case 'getTicket':
        $ticket_id = intval($_GET['ticket_id'] ?? 0);
        $ticket = $crud->getTicketById($ticket_id, $user['user_id']);
        $messages = $crud->getTicketMessages($ticket_id, $user['user_id']);

        if(!$ticket) {
            echo json_encode(['status'=>'error','message'=>'Ticket not found']);
            exit;
        }

        echo json_encode([
            'status'=>'success',
            'ticket'=>$ticket,
            'messages'=>$messages
        ]);
        break;

    // ================= CREATE TICKET =================
    case 'createTicket':
        $category = $_POST['category'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if(!$category || !$subject || !$message){
            echo json_encode(['status'=>'error','message'=>'All fields required']);
            exit;
        }

        $success = $crud->createTicket($user['user_id'],$category,$subject,$message);
        echo json_encode(['status'=> $success ? 'success':'error']);
        break;

    // ================= ADD REPLY =================
    case 'addReply':
        $ticket_id = intval($_POST['ticket_id'] ?? 0);
        $message = $_POST['message'] ?? '';

        if(!$message || !$ticket_id){
            echo json_encode(['status'=>'error','message'=>'Invalid input']);
            exit;
        }

        $success = $crud->addReply($ticket_id, $user['user_id'], $message);
        echo json_encode(['status'=> $success ? 'success':'error']);
        break;

    // ================= DELETE TICKET =================
    case 'deleteTicket':
        $ticket_id = intval($_POST['ticket_id'] ?? 0);
        $success = $crud->deleteTicket($ticket_id, $user['user_id']);
        echo json_encode(['status'=> $success ? 'success':'error']);
        break;

    default:
        echo json_encode(['status'=>'error','message'=>'Invalid action']);
        break;
}
