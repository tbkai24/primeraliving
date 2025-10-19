<?php
require_once '../config/config.php';
require_once '../config/CRUD.php';

$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch($action){

    // ===== READ =====
    case 'read':
        $admins = $crud->getAllAdmins();
        echo json_encode(['success'=>true,'data'=>$admins]);
        break;

    // ===== CREATE =====
    case 'create':
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'admin';

        if(!$fullname || !$email || !$password){
            echo json_encode(['success'=>false,'message'=>'All fields required']); exit;
        }

        if($crud->getAdminByEmail($email)){
            echo json_encode(['success'=>false,'message'=>'Email already exists']); exit;
        }

        $id = $crud->createAdmin($fullname,$email,$password,$role);
        echo json_encode([
            'success'=>$id ? true:false,
            'message'=>$id ? 'Admin created successfully':'Failed to create admin'
        ]);
        break;

    // ===== UPDATE =====
    case 'update':
        $id = $_POST['admin_id'] ?? 0;
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'admin';
        $password = $_POST['password'] ?? '';

        if(!$id || !$fullname || !$email){
            echo json_encode(['success'=>false,'message'=>'Invalid data']); exit;
        }

        if($password){
            $result = $crud->updateAdmin($id,$fullname,$email,$role,$password);
        } else {
            $result = $crud->updateAdmin($id,$fullname,$email,$role);
        }

        echo json_encode([
            'success'=>$result ? true:false,
            'message'=>$result ? 'Admin updated successfully':'Failed to update admin'
        ]);
        break;

    // ===== DELETE =====
    case 'delete':
        $id = $_POST['admin_id'] ?? 0;
        $result = $crud->deleteAdmin($id);
        echo json_encode([
            'success'=>$result ? true:false,
            'message'=>$result ? 'Admin deleted successfully':'Failed to delete admin'
        ]);
        break;

    default:
        echo json_encode(['success'=>false,'message'=>'Invalid action']);
        break;
}
