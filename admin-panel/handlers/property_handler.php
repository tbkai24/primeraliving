<?php
require_once '../config/config.php';
require_once '../config/CRUD.php';

$crud = new CRUD($pdo);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ===== READ PROPERTIES =====
    case 'read':
        $properties = $crud->getAllProperties();
        echo json_encode(['success' => true, 'data' => $properties]);
        break;

    // ===== ADD PROPERTY =====
    case 'add':
        $unit_name = $_POST['unit_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $rent_amount = $_POST['rent_amount'] ?? 0;
        $status = $_POST['availability_status'] ?? 'available';
        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../uploads/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $filename = time() . '_' . basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = $filename;
            }
        }

        $result = $crud->createProperty($unit_name, $description, $address, $rent_amount, $status, $image);
        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Property added successfully!' : 'Failed to add property.'
        ]);
        break;

    // ===== GET SINGLE PROPERTY =====
    case 'get':
        $property_id = $_POST['property_id'] ?? 0;
        $property = $crud->getPropertyById($property_id);
        echo json_encode([
            'success' => (bool)$property,
            'data' => $property,
            'message' => $property ? '' : 'Property not found.'
        ]);
        break;

    // ===== UPDATE PROPERTY =====
    case 'update':
        $property_id = $_POST['property_id'] ?? 0;
        $unit_name = $_POST['unit_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $rent_amount = $_POST['rent_amount'] ?? 0;
        $status = $_POST['availability_status'] ?? 'available';
        $image = null;

        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../uploads/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $filename = time() . '_' . basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $filename;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = $filename;
            }
        }

        $result = $crud->updateProperty($property_id, $unit_name, $description, $address, $rent_amount, $status, $image);
        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Property updated successfully!' : 'Failed to update property.'
        ]);
        break;

    // ===== DELETE PROPERTY =====
    case 'delete':
        $property_id = $_POST['property_id'] ?? 0;
        $result = $crud->deleteProperty($property_id);
        echo json_encode([
            'success' => (bool)$result,
            'message' => $result ? 'Property deleted successfully!' : 'Failed to delete property.'
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
