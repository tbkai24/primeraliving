<?php
session_start();
define("ADMINURL", "http://localhost/primeraliving/admin-panel");

$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'login.php') {
    if (!isset($_SESSION['admin']) || $_SESSION['admin']['role'] !== 'admin') {
        header("Location: " . ADMINURL . "/auth/login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Primera Living Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="<?php echo ADMINURL; ?>/asset/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo ADMINURL; ?>/asset/fontawesome/css/all.min.css" rel="stylesheet">
  <link href="<?php echo ADMINURL; ?>/asset/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body class="<?php echo isset($page) ? $page : ''; ?>">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-custom">
  <div class="container-fluid">
    <!-- Brand -->
    <a class="navbar-brand text-white fw-bold" href="<?php echo ADMINURL; ?>/index.php">
      <i class="fas fa-home me-2"></i>Primera Living Admin
    </a>

    <?php if (isset($_SESSION['admin'])): ?>
      <div class="d-flex align-items-center ms-auto">
        <!-- Toggle button (mobile only) -->
        <button class="btn btn-sm btn-light d-lg-none me-2" id="toggleSidebar">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Logout button -->
        <a href="<?php echo ADMINURL; ?>/auth/logout.php"
           class="btn btn-outline-light btn-sm fw-semibold px-3">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </div>
    <?php endif; ?>
  </div>
</nav>

<?php if (isset($_SESSION['admin'])): ?>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul class="list-unstyled m-0">
      <li><a href="<?php echo ADMINURL; ?>/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
      <li><a href="<?php echo ADMINURL; ?>/users/users.php"><i class="fas fa-users me-2"></i>User Management</a></li>
      <li><a href="<?php echo ADMINURL; ?>/admins/admins.php"><i class="fas fa-user-shield me-2"></i>Admin Management</a></li>
      <li><a href="<?php echo ADMINURL; ?>/properties/properties.php"><i class="fas fa-building me-2"></i>Property Management</a></li>
      
      <li><a href="<?php echo ADMINURL; ?>/transactions/transactions.php"><i class="fas fa-credit-card me-2"></i>Transactions</a></li>
      <li><a href="<?php echo ADMINURL; ?>/leases/lease.php"><i class="fas fa-key me-2"></i>Leases</a></li>
      <li><a href="<?php echo ADMINURL; ?>/supports/supports.php"><i class="fas fa-life-ring me-2"></i>Support</a></li>
      <li><a href="<?php echo ADMINURL; ?>/reports/reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a></li>
      <li><a href="<?php echo ADMINURL; ?>/auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
  </div>

  <!-- Content -->
  <div class="content flex-grow-1 p-4">
<?php endif; ?>
