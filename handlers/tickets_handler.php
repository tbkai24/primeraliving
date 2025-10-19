<?php
require_once '../config/config.php';
require_once '../config/crud.php';

$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {

    // ========== List Tickets ==========
    case 'list':
        $user_id = $_GET['user_id'] ?? 0;
        if(!$user_id){
            echo json_encode([]);
            exit;
        }
        $tickets = $crud->getUserTickets($user_id);
        echo json_encode($tickets);
        break;

    // ========== Get Messages ==========
    case 'messages':
        $ticket_id = $_GET['ticket_id'] ?? 0;
        if(!$ticket_id){
            echo json_encode([]);
            exit;
        }
        $messages = $crud->getTicketMessages($ticket_id);
        echo json_encode($messages);
        break;

    // ========== Create Ticket ==========
    case 'create_ticket':
        $user_id = $_POST['user_id'] ?? 0;
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $category = $_POST['category'] ?? 'Other';

        if(!$user_id || !$subject || !$message){
            echo json_encode(['success'=>false,'message'=>'Invalid input.']);
            exit;
        }

        $inserted = $crud->addTicket($user_id, $subject, $message, $category);
        if($inserted){
            echo json_encode(['success'=>true,'ticket_id'=>$inserted]);
        } else {
            echo json_encode(['success'=>false,'message'=>'Failed to create ticket.']);
        }
        break;

    // ========== Add Reply ==========
    case 'add_reply':
        $ticket_id = $_POST['ticket_id'] ?? 0;
        $sender = $_POST['sender'] ?? '';
        $sender_name = $_POST['sender_name'] ?? '';
        $message = trim($_POST['message'] ?? '');

        if(!$ticket_id || !$sender || !$sender_name || !$message){
            echo json_encode(['success'=>false,'message'=>'Invalid input.']);
            exit;
        }

        $added = $crud->addTicketReply($ticket_id, $sender, $sender_name, $message);
        echo json_encode(['success'=>$added]);
        break;

    // ========== Update Ticket Status ==========
    case 'update_status':
        $ticket_id = $_POST['ticket_id'] ?? 0;
        $status = $_POST['status'] ?? '';

        if(!$ticket_id || !$status){
            echo json_encode(['success'=>false,'message'=>'Invalid input.']);
            exit;
        }

        $updated = $crud->updateTicketStatus($ticket_id, $status);
        echo json_encode(['success'=>$updated]);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action.']);
        break;
}
