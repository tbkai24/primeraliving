<?php
// ===================================================
// =================== ADMIN DASHBOARD ==============
// ===================================================
$page = 'dashboard';
include 'layouts/header.php';

// Load config and CRUD class
require "config/config.php";
require "config/CRUD.php";

// Initialize CRUD
$crud = new CRUD($pdo);

// ========================
// Safety Checks
// ========================
if(!class_exists('CRUD')){
    die("Error: CRUD class not found.");
}

if(!method_exists($crud, 'getAllProperties')){
    die("Error: getAllProperties() method not found in CRUD class.");
}

// ========================
// Fetch Dashboard Data
// ========================
$properties   = $crud->getAllProperties() ?: [];
$users        = $crud->getAllUsers() ?: [];
$leases       = $crud->getAllLeases() ?: [];
$tickets      = $crud->getTickets() ?: [];
$transactions = $crud->getAllTransactions() ?: [];

// Counts
$totalProperties = count($properties);
$totalRenters    = count($users);
$activeLeases    = count($leases);
$pendingApps     = 5; // Placeholder if you don't have a applications table
$supportTickets  = count($tickets);

// Monthly Revenue
$monthlyRevenue = 0;
$currentMonth = date('m');
foreach ($transactions as $t) {
    if(isset($t['created_at']) && date('m', strtotime($t['created_at'])) == $currentMonth){
        $monthlyRevenue += $t['amount'];
    }
}

// ========================
// Dashboard Cards
// ========================
$dashboardCards = [
    ["label" => "Total Properties", "value" => $totalProperties, "icon" => "building", "color" => "#2A6F9E"],
    ["label" => "Total Renters", "value" => $totalRenters, "icon" => "users", "color" => "#198754"],
    ["label" => "Active Leases", "value" => $activeLeases, "icon" => "file-signature", "color" => "#ffc107"],
    ["label" => "Pending Applications", "value" => $pendingApps, "icon" => "hourglass-half", "color" => "#dc3545"],
    ["label" => "Monthly Revenue", "value" => "₱" . number_format($monthlyRevenue, 2), "icon" => "dollar-sign", "color" => "#0dcaf0"],
    ["label" => "Support Tickets", "value" => $supportTickets, "icon" => "life-ring", "color" => "#6c757d"],
];
?>

<!-- ===== Dashboard Content ===== -->
<h2 class="dashboard-header"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>

<div class="row g-4">
    <?php foreach ($dashboardCards as $card): ?>
        <div class="col-xl-4 col-md-6">
            <div class="dashboard-card shadow rounded p-3">
                <div class="card-top-bar" style="background: <?php echo $card['color']; ?>;"></div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <div class="card-label fw-bold"><?php echo $card['label']; ?></div>
                        <div class="card-value h4"><?php echo $card['value']; ?></div>
                    </div>
                    <div class="dashboard-icon display-6" style="color: <?php echo $card['color']; ?>;">
                        <i class="fas fa-<?php echo $card['icon']; ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'layouts/footer.php'; ?>
