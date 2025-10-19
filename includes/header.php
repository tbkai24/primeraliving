<?php
include_once __DIR__ . '/../config/config.php';
define("APPURL", "http://localhost/primeraliving");
// Notifications & Profile items
$notifications = [
    ['icon' => 'fas fa-file-alt', 'text' => 'Your rental application is under review', 'link' => APPURL . '/user/my-application.php'],
    ['icon' => 'fas fa-calendar-day', 'text' => 'Upcoming rent due on May 15', 'link' => APPURL . '/user/payment-schedule.php'],
    ['icon' => '', 'text' => 'View all notifications', 'link' => APPURL . '/user/notifications.php']
];

$profileItems = [
    ['icon' => 'fas fa-user', 'text' => 'User Dashboard', 'link' => APPURL . '/user/UserDashboard.php'], 
    ['icon' => 'fas fa-receipt', 'text' => 'Transaction History', 'link' => APPURL . '/user/transaction-history.php'],
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
   <!-- AOS (Animate On Scroll) CSS nilaggay ko toh pres --> 
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <link href="<?php echo APPURL; ?>/asset/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo APPURL; ?>/asset/fontawesome/css/all.min.css" rel="stylesheet">
  <link href="<?php echo APPURL; ?>/asset/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php
  $isHomePage = basename($_SERVER['SCRIPT_NAME']) === 'index.php' && strpos($_SERVER['SCRIPT_NAME'], '/auth/') === false;
?>
<nav class="navbar navbar-expand-lg fixed-top cool-navbar <?php echo $isHomePage ? 'animated-navbar' : ''; ?>">


  <div class="container">
    <a class="navbar-brand" href="<?php echo APPURL; ?>/index.php">
      <i class="fas fa-home me-2"></i>Primera Living
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/listings.php">Listings</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/how.php">How It Works</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/about.php">About Us</a></li>

        <?php if (isset($_SESSION['user'])): ?>
          <!-- Notifications -->
          <li class="nav-item dropdown mx-2">
            <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-bell fa-lg"></i>
              <span class="badge rounded-pill">3</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
              <li class="dropdown-header fw-bold">Notifications</li>
              <?php foreach ($notifications as $notification): ?>
                <li>
                  <a class="dropdown-item" href="<?php echo $notification['link']; ?>">
                    <?php if ($notification['icon']): ?>
                      <i class="<?php echo $notification['icon']; ?> me-2"></i>
                    <?php endif; ?>
                    <?php echo $notification['text']; ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>

          <!-- Profile -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle fa-lg me-2"></i>
              <span>
                <?php
                  if (isset($_SESSION['user']['first_name'])) {
                      echo htmlspecialchars($_SESSION['user']['first_name'] . 
                          (!empty($_SESSION['user']['last_name']) ? ' ' . $_SESSION['user']['last_name'] : '')
                      );
                  } else {
                      echo 'Profile';
                  }
                ?>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <?php foreach ($profileItems as $item): ?>
                <li>
                  <a class="dropdown-item <?php echo isset($item['class']) ? $item['class'] : ''; ?>" href="<?php echo $item['link']; ?>">
                    <i class="<?php echo $item['icon']; ?> me-2"></i><?php echo $item['text']; ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>/auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
