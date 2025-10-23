<?php
include __DIR__ . '/../../includes/header.php';

// 🔒 Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['login_message'] = "You must log in to access this page.";
    header("Location: ../../auth/login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!-- 🌸 Primera Living Themed Background -->
<div class="transaction-bg d-flex flex-column min-vh-100 py-5">
  <div class="container py-4 fade-in">
    <h3 class="mb-4 fw-bold text-primary text-center">
      <i class="fas fa-ticket-alt me-2"></i>My Tickets
    </h3>

    <!-- ✅ Alert -->
    <div id="alertBox" class="mb-3" style="display:none;">
      <div class="alert fade show mb-0" role="alert"></div>
    </div>

    <!-- 🎫 Tickets Section -->
    <div class="card border-0 shadow transaction-card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 fw-semibold text-secondary">
            <i class="fas fa-list me-2 text-peach"></i>Your Support Tickets
          </h6>
          <a href="create-ticket.php" class="btn btn-peach btn-sm">
            <i class="fas fa-plus-circle me-1"></i>Create Ticket
          </a>
        </div>

        <!-- 🧾 Tickets Table -->
        <div class="table-responsive">
          <table class="table table-hover align-middle text-center mb-0" id="ticketsTable">
            <thead class="table-peach">
              <tr>
                <th>#</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="6" class="text-center text-muted">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script>
const currentUserId = <?= json_encode($user['user_id']) ?>;
</script>
<script src="../../asset/js/tickets.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
