<?php
include __DIR__ . '/../../includes/header.php';

// 🔒 Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    $_SESSION['login_message'] = "You must log in to access this page.";
    header("Location: ../../auth/login.php");
    exit();
}
?>

<!-- 🌸 Primera Living Themed Background -->
<div class="tickets-bg py-5">
  <div class="container py-4 fade-in">

    <!-- 📝 Page Heading -->
    <h3 class="mb-4 fw-bold text-primary text-center">
      <i class="fas fa-ticket-alt me-2"></i>Create New Ticket
    </h3>

    <!-- ✅ Alert Container -->
    <div id="alertBox" class="mb-3" style="display:none;">
      <div class="alert fade show mb-0" role="alert"></div>
    </div>

    <!-- 💳 Ticket Form Card -->
    <div class="card border-0 shadow ticket-card">
      <div class="card-body">
        <form id="createTicketForm">
          <!-- Category -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category" class="form-select" required>
              <option value="Payment">Payment</option>
              <option value="Unit">Unit</option>
              <option value="Maintenance">Maintenance</option>
              <option value="Other" selected>Other</option>
            </select>
          </div>

          <!-- Subject -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Subject</label>
            <input type="text" name="subject" class="form-control" placeholder="Enter subject..." required>
          </div>

          <!-- Message -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Message</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Describe your concern..." required></textarea>
          </div>

          <!-- Submit Button -->
          <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class="fa fa-paper-plane me-1"></i>Submit Ticket
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ⬅️ Back Button -->
    <div class="text-center mt-3">
      <a href="tickets.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to My Tickets
      </a>
    </div>

  </div>
</div>

<!-- JS -->
<script src="../../asset/js/tickets.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
