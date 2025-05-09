<?php
session_start();
define("ADMINURL", "http://localhost/primeraliving/admin-panel");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Primera Living</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo ADMINURL; ?>/asset/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo ADMINURL; ?>/asset/fontawesome/css/all.min.css" rel="stylesheet">
  <link href="<?php echo ADMINURL; ?>/asset/css/style.css" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#EBE7DB; display:flex; flex-direction:column; min-height:100vh;">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light" style="background-color:#6f42c1; color:white;">
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="<?php echo ADMINURL; ?>/index.php">
      <i class="fas fa-home me-2"></i>Primera Living Admin Panel
    </a>
    <?php if (isset($_SESSION['admin'])): ?>
      <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3"><i class="fas fa-user-shield"></i> Admin</span>
      </div>
    <?php endif; ?>
  </div>
</nav>

<?php if (isset($_SESSION['admin']) && $_SESSION['admin']['role'] === 'admin'): ?>
<!-- Begin layout row: Sidebar + Content -->
<div class="container-fluid" style="flex:1; display:flex; padding-top:20px;">
  <!-- Sidebar -->
  <div class="col-md-3 col-lg-2 p-0 bg-light" style="min-height: 25vh; border-right:1px solid #ccc;">
    <div class="list-group list-group-flush">
      <a href="<?php echo ADMINURL; ?>/index.php" class="list-group-item list-group-item-action">Dashboard</a>
      <a href="<?php echo ADMINURL; ?>/users/users.php" class="list-group-item list-group-item-action">User Management</a>
      <a href="<?php echo ADMINURL; ?>/admins/admins.php" class="list-group-item list-group-item-action">Admin Management</a>
      <a href="<?php echo ADMINURL; ?>/properties/properties.php" class="list-group-item list-group-item-action">Property Management</a>
      <a href="<?php echo ADMINURL; ?>/application/application.php" class="list-group-item list-group-item-action">Rental Applications</a>
      <a href="<?php echo ADMINURL; ?>/transactions/transactions.php" class="list-group-item list-group-item-action">Transactions</a>
      <a href="<?php echo ADMINURL; ?>/payments/schedule.php" class="list-group-item list-group-item-action">Payment Schedule</a>
      <a href="<?php echo ADMINURL; ?>/supports/supports.php" class="list-group-item list-group-item-action">Support Tickets</a>
      <a href="<?php echo ADMINURL; ?>/notifications/notifications.php" class="list-group-item list-group-item-action">Notifications</a>
      <a href="<?php echo ADMINURL; ?>/auth/logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
    </div>
  </div>
<?php endif; ?>

</body>
</html>
