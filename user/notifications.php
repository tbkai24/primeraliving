<?php
include '../config/config.php';
include '../includes/header.php';

// Sample notifications — in production, fetch from database
$notifications = [
  [
    'icon' => 'fas fa-file-alt',
    'message' => 'Your rental application is under review',
    'link' => APPURL . '/user/my-application.php',
    'date' => 'May 5, 2025',
  ],
  [
    'icon' => 'fas fa-calendar-day',
    'message' => 'Upcoming rent due on May 15',
    'link' => APPURL . '/user/payment-schedule.php',
    'date' => 'May 4, 2025',
  ],
  [
    'icon' => 'fas fa-life-ring',
    'message' => 'Support ticket updated',
    'link' => APPURL . '/user/tickets.php',
    'date' => 'May 3, 2025',
  ]
];
?>

<div class="container py-5" style="min-height: 100vh;">
  <h2 class="mb-4">Notifications</h2>

  <div class="list-group">
    <?php foreach ($notifications as $notif): ?>
      <a href="<?php echo $notif['link']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
          <div class="fw-bold"><i class="<?php echo $notif['icon']; ?> me-2 text-primary"></i><?php echo htmlspecialchars($notif['message']); ?></div>
          <small class="text-muted"><?php echo htmlspecialchars($notif['date']); ?></small>
        </div>
        <i class="fas fa-chevron-right text-muted"></i>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
