<?php
session_start();
include '../config/config.php';
include '../config/CRUD.php';

header('Content-Type: application/json');

$crud = new CRUD($pdo);

$action = $_POST['action'] ?? '';
$response = ['success'=>false, 'message'=>'Invalid action'];

// ------------------ FILTER HELPER ------------------
function filterUsers($users, $search, $status, $from, $to){
    return array_filter($users, function($u) use ($search, $status, $from, $to){
        // Search by name or email
        $match = true;
        if($search){
            $match = stripos($u['first_name'].' '.$u['last_name'], $search)!==false
                     || stripos($u['email'], $search)!==false;
        }
        if($match && $status && $status !== 'all'){
            $match = $u['status'] === $status;
        }
        if($match && $from){
            $match = strtotime($u['created_at']) >= strtotime($from);
        }
        if($match && $to){
            $match = strtotime($u['created_at']) <= strtotime($to);
        }
        return $match;
    });
}

switch($action){

    // ------------------ READ ALL USERS ------------------
    case 'read':
        $users = $crud->getAllUsers(); // Make sure this exists in CRUD.php

        // Optional filters
        $search = trim($_POST['search'] ?? '');
        $status = $_POST['status'] ?? '';
        $from = $_POST['from_date'] ?? '';
        $to = $_POST['to_date'] ?? '';

        if($search || $status || $from || $to){
            $users = filterUsers($users, $search, $status, $from, $to);
        }

        // Format created_at
        foreach($users as &$u){
            $u['created_at'] = date('F j, Y h:i:s A', strtotime($u['created_at']));
        }

        $response = ['success'=>true, 'data'=>$users];
        break;

    // ------------------ GET SINGLE USER ------------------
    case 'get_user':
        $user_id = $_POST['user_id'] ?? 0;
        if(!$user_id){
            $response = ['success'=>false, 'message'=>'User ID required'];
            break;
        }
        $user = $crud->getUserById($user_id); // Add this function in CRUD.php
        if($user){
            $user['created_at'] = date('F j, Y h:i:s A', strtotime($user['created_at']));
            $response = ['success'=>true, 'data'=>$user];
        } else {
            $response = ['success'=>false, 'message'=>'User not found'];
        }
        break;

    // ------------------ UPDATE USER STATUS ------------------
    case 'update_status':
        $user_id = $_POST['user_id'] ?? 0;
        $status = $_POST['status'] ?? '';
        if(!$user_id || !$status){
            $response = ['success'=>false, 'message'=>'User ID and status required'];
            break;
        }
        $success = $crud->updateUserStatus($user_id, $status); // Add this function in CRUD.php
        $response = $success 
            ? ['success'=>true, 'message'=>'User status updated successfully'] 
            : ['success'=>false, 'message'=>'Failed to update status'];
        break;

    // ------------------ DELETE USER ------------------
    case 'delete':
        $user_id = $_POST['user_id'] ?? 0;
        if(!$user_id){
            $response = ['success'=>false, 'message'=>'User ID required'];
            break;
        }
        $success = $crud->deleteUser($user_id); // Add this function in CRUD.php
        $response = $success
            ? ['success'=>true, 'message'=>'User deleted successfully']
            : ['success'=>false, 'message'=>'Failed to delete user'];
        break;
}

echo json_encode($response);
exit;
?>
