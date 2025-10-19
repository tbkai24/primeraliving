<?php
require_once '../config/config.php';
require_once '../config/crud.php';
$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action){

    // ===== List Tickets =====
    case 'list':
        $keyword = $_GET['keyword'] ?? '';
        $status = $_GET['status'] ?? '';
        $category = $_GET['category'] ?? '';
        $tickets = $crud->getTickets($keyword, $status, $category);
        echo json_encode($tickets);
        break;

    // ===== Get Messages =====
    case 'messages':
        $ticket_id = $_GET['ticket_id'] ?? 0;
        if(!$ticket_id) { echo json_encode([]); exit; }
        echo json_encode($crud->getTicketMessages($ticket_id));
        break;

    // ===== Add Reply =====
    case 'add_reply':
        $ticket_id = $_POST['ticket_id'] ?? 0;
        $sender = $_POST['sender'] ?? '';
        $sender_name = $_POST['sender_name'] ?? '';
        $message = trim($_POST['message'] ?? '');
        if(!$ticket_id || !$sender || !$sender_name || !$message){
            echo json_encode(['success'=>false,'message'=>'Invalid input.']); exit;
        }
        echo json_encode(['success'=>$crud->addTicketReply($ticket_id,$sender,$sender_name,$message)]);
        break;

    // ===== Update Status =====
    case 'update_status':
        $ticket_id = $_POST['ticket_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if(!$ticket_id || !$status){
            echo json_encode(['success'=>false,'message'=>'Invalid input.']); exit;
        }
        echo json_encode(['success'=>$crud->updateTicketStatus($ticket_id,$status)]);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action']);
        break;
}
