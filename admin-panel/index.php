<?php
$page = 'dashboard';
include 'layouts/header.php';

require "../config/config.php";
require "../config/crud.php";

// Initialize CRUD
$crud = new CRUD($pdo);

// Fetch real counts
$totalProperties   = count($crud->getAllProperties());
$totalRenters      = count($crud->getAllUsers());
$activeLeases      = count($crud->getAllLeases());

// You can add a query for pending applications if you have a table for it
// For now, let's set a dummy value
$pendingApps       = 5;

// Fetch total revenue (sum of payments)
$payments = $crud->getAllTransactions();
$monthlyRevenue = 0;
$currentMonth = date('m');
foreach ($payments as $p) {
    if (date('m', strtotime($p['created_at'])) == $currentMonth) {
        $monthlyRevenue += $p['amount'];
    }
}

// Fetch support tickets count
$supportTickets = count($crud->getTickets());
?>

<!-- ===== Dashboard Content ===== -->
<h2 class="dashboard-header"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>

<div class="row g-4">
  <?php
  $dashboardCards = [
    ["label" => "Total Properties", "value" => $totalProperties, "icon" => "building", "color" => "#2A6F9E"],
    ["label" => "Total Renters", "value" => $totalRenters, "icon" => "users", "color" => "#198754"],
    ["label" => "Active Leases", "value" => $activeLeases, "icon" => "file-signature", "color" => "#ffc107"],
    ["label" => "Pending Applications", "value" => $pendingApps, "icon" => "hourglass-half", "color" => "#dc3545"],
    ["label" => "Monthly Revenue", "value" => "₱" . number_format($monthlyRevenue), "icon" => "dollar-sign", "color" => "#0dcaf0"],
    ["label" => "Support Tickets", "value" => $supportTickets, "icon" => "life-ring", "color" => "#6c757d"],
  ];

  foreach ($dashboardCards as $card): ?>
    <div class="col-xl-4 col-md-6">
      <div class="dashboard-card">
        <div class="card-top-bar" style="background: <?php echo $card['color']; ?>;"></div>
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-label"><?php echo $card['label']; ?></div>
            <div class="card-value"><?php echo $card['value']; ?></div>
          </div>
          <div class="dashboard-icon" style="color: <?php echo $card['color']; ?>;">
            <i class="fas fa-<?php echo $card['icon']; ?>"></i>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php include 'layouts/footer.php'; ?>
