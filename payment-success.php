<?php
include __DIR__ . '/includes/header.php';
?>

<section class="d-flex align-items-center justify-content-center" style="min-height: 80vh; background: #f8f9fa; padding: 40px 20px;">
  <div class="container text-center">
    <div class="card shadow p-5 mx-auto" style="max-width: 550px;">
      <h2 class="text-success mb-3">🏡 Booking Confirmed!</h2>
      <p class="mb-4">Thank you for your payment. Your rental has been successfully confirmed with <strong>Primera Living</strong>.</p>
      <p class="text-muted">We’ve reserved your unit and sent a confirmation to your email.</p>
      <div class="mt-4">
        <a href="listings.php" class="btn btn-outline-secondary me-2">Back to Listings</a>
        <a href="index.php" class="btn" style="background-color:#2A6F9E; color:white;">Go to Dashboard</a>
      </div>
    </div>
  </div>
</section>

<?php
include __DIR__ . '/includes/footer.php';
?>
