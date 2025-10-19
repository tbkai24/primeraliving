<?php
include __DIR__ . '/../config/CRUD.php';
include __DIR__ . '/../includes/header.php';

// 🔒 Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['login_message'] = "You must log in to access this page.";
    header("Location: ../auth/login.php");
    exit();
}
?>

<!-- 🌸 Primera Living Themed Background -->
<div class="transaction-bg d-flex flex-column min-vh-100 py-5">
  <div class="container py-4 fade-in">
    <h3 class="mb-4 fw-bold text-primary text-center">
      <i class="fas fa-receipt me-2"></i>Transaction History
    </h3>

    <!-- ✅ Alert (success or error) -->
    <div id="alertContainer" class="mb-3" style="display:none;">
      <div class="alert fade show mb-0" role="alert"></div>
    </div>

    <!-- 🧾 Date filter for requesting PDF -->
    <div class="card border-0 shadow transaction-card mb-4">
      <div class="card-body">
        <form id="requestPdfForm" class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label fw-semibold">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="col-md-5">
            <label class="form-label fw-semibold">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn w-100 peach-btn">
              <i class="fas fa-file-pdf me-1"></i>Request PDF
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 💰 Transactions Table -->
    <div class="card border-0 shadow transaction-card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0 fw-semibold text-secondary">
            <i class="fas fa-list me-2 text-peach"></i>Your Transactions
          </h6>
          <button id="refreshBtn" class="btn btn-outline-peach btn-sm">
            <i class="fas fa-sync-alt me-1"></i>Refresh
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle text-center mb-0" id="transactionTable">
            <thead class="table-peach">
              <tr>
                <th>#</th>
                <th>Invoice ID</th>
                <th>Property</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Method</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="../asset/js/transaction_history.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
