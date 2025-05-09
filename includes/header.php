<?php
session_start();
define("APPURL", "http://localhost/primeraliving");

$notifications = [
    ['icon' => 'fas fa-file-alt', 'text' => 'Your rental application is under review', 'link' => APPURL . '/user/my-application.php'],
    ['icon' => 'fas fa-calendar-day', 'text' => 'Upcoming rent due on May 15', 'link' => APPURL . '/user/payment-schedule.php'],
    ['icon' => '', 'text' => 'View all notifications', 'link' => APPURL . '/user/notifications.php']
];

$profileItems = [
    ['icon' => 'fas fa-receipt', 'text' => 'Transaction History', 'link' => APPURL . '/user/transaction-history.php'],
    ['icon' => 'fas fa-file-alt', 'text' => 'My Application', 'link' => APPURL . '/user/my-application.php'],
    ['icon' => 'fas fa-home', 'text' => 'My Rentals', 'link' => APPURL . '/user/my-rentals.php'],
    ['icon' => 'fas fa-calendar-alt', 'text' => 'Payment Schedule', 'link' => APPURL . '/user/payment-schedule.php'],
    ['icon' => 'fas fa-life-ring', 'text' => 'Support Tickets', 'link' => APPURL . '/user/tickets/tickets.php'],
    ['icon' => 'fas fa-cog', 'text' => 'Settings', 'link' => APPURL . '/user/settings.php'],
    ['icon' => 'fas fa-sign-out-alt', 'text' => 'Logout', 'link' => APPURL . '/auth/logout.php', 'class' => 'text-danger']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Primera Living</title>

  <link href="<?php echo APPURL; ?>/asset/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo APPURL; ?>/asset/fontawesome/css/all.min.css" rel="stylesheet">
  <link href="<?php echo APPURL; ?>/asset/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="padding: 10px 0;">
  <div class="container">
    <a class="navbar-brand fs-5" href="<?php echo APPURL; ?>/index.php">
      <i class="fas fa-home me-2"></i>Primera Living
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav fs-5">
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/listings.php">Listings</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/how.php">How It Works</a></li>

        <?php if (isset($_SESSION['user'])): ?>
          <!-- Notification Icon -->
          <li class="nav-item dropdown mx-2">
            <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell fa-lg"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown">
              <li class="dropdown-header fw-bold">Notifications</li>
              <?php foreach ($notifications as $notification): ?>
                <li>
                  <a class="dropdown-item small" style="background-color: #fff !important; color: #212529 !important; width: 300px !important;" href="<?php echo $notification['link']; ?>">
                    <?php if ($notification['icon']): ?>
                      <i class="<?php echo $notification['icon']; ?> me-2"></i>
                    <?php endif; ?>
                    <?php echo $notification['text']; ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>

          <!-- Profile Icon Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Profile">
              <i class="fas fa-user-circle fa-lg"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown" >
              <?php foreach ($profileItems as $item): ?>
                <li>
                  <a class="dropdown-item <?php echo isset($item['class']) ? $item['class'] : ''; ?>" href="<?php echo $item['link']; ?>" style="background-color: #fff !important; color: #212529 !important; width: 300px !important;">
                    <i class="<?php echo $item['icon']; ?> me-2"></i><?php echo $item['text']; ?>
                  </a>
                </li>
              <?php endforeach; ?>
              <li><hr class="dropdown-divider"></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
