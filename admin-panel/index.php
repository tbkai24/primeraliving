<?php
include 'layouts/header.php';
require "../config/config.php";

// Dummy values
$totalProperties = 12;
$totalRenters = 35;
$activeLeases = 20;
$pendingApps = 5;
$monthlyRevenue = 68500;
$supportTickets = 8; // Updated value for Support Tickets
?>

<!-- Main Content wrapper -->
<div class="col-md-9 col-lg-10 p-4" style="background-color:#EBE7DB;">
  <h2 class="mb-4">Dashboard Overview</h2>

  <div class="row g-4">
    <?php
    $dashboardCards = [
      ["label" => "Total Properties", "value" => $totalProperties, "icon" => "building", "color" => "primary"],
      ["label" => "Total Renters", "value" => $totalRenters, "icon" => "users", "color" => "success"],
      ["label" => "Active Leases", "value" => $activeLeases, "icon" => "file-signature", "color" => "warning"],
      ["label" => "Pending Applications", "value" => $pendingApps, "icon" => "hourglass-half", "color" => "danger"],
      ["label" => "Monthly Revenue", "value" => "₱" . number_format($monthlyRevenue), "icon" => "dollar-sign", "color" => "info"], // Updated icon for Monthly Revenue
      ["label" => "Support Tickets", "value" => $supportTickets, "icon" => "life-ring", "color" => "secondary"], // Updated card for Support Tickets
    ];

    foreach ($dashboardCards as $card): ?>
      <div class="col-md-4 d-flex">
        <div class="card shadow-sm border-start border-4 border-<?php echo $card['color']; ?> flex-fill w-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title text-muted"><?php echo $card['label']; ?></h5>
              <h3 class="card-text"><?php echo $card['value']; ?></h3>
            </div>
            <i class="fas fa-<?php echo $card['icon']; ?> fa-2x text-<?php echo $card['color']; ?> align-self-end"></i>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<?php include 'layouts/footer.php'; ?>
