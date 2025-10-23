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
      <i class="fas fa-ticket-alt me-2"></i>View Ticket
    </h3>

    <!-- ✅ Alert -->
    <div id="alertBox" class="mb-3" style="display:none;">
      <div class="alert fade show mb-0" role="alert"></div>
    </div>

    <!-- 🎫 Ticket Info Card -->
    <div class="card border-0 shadow transaction-card mb-4">
      <div class="card-body">
        <div id="ticketInfo">
          <!-- Ticket subject, category, status, created_at will be loaded here by JS -->
          <p class="text-center text-muted">Loading ticket details...</p>
        </div>
      </div>
    </div>

    <!-- 💬 Conversation -->
    <div class="card border-0 shadow transaction-card mb-4">
      <div class="card-body">
        <h6 class="fw-semibold text-secondary mb-3"><i class="fas fa-comments me-2 text-peach"></i>Conversation</h6>
        <div id="ticketMessages" class="mb-3" style="min-height: 150px;">
          <!-- Messages loaded here via JS -->
          <p class="text-center text-muted">Loading messages...</p>
        </div>

        <!-- ✍️ Reply Form -->
        <form id="replyForm" style="display:none;">
          <div class="mb-3">
            <label for="replyMessage" class="form-label fw-semibold">Your Reply</label>
            <textarea class="form-control" id="replyMessage" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-peach">
            <i class="fas fa-paper-plane me-1"></i>Send Reply
          </button>
        </form>
      </div>
    </div>

    <!-- ⬅️ Back Button -->
    <div class="text-center mb-4">
      <a href="tickets.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to My Tickets
      </a>
    </div>
  </div>
</div>

<!-- JS -->
<script>
  const ticketId = <?= isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0 ?>;
</script>
<script src="../../asset/js/tickets.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
