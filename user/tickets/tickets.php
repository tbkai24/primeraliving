<?php
session_start();
include '../../includes/header.php';
?>

<div class="tickets-bg">
  <div class="container">
    <h2 class="mb-4 tickets-title">
      <i class="fas fa-ticket-alt me-2"></i>Support Tickets
    </h2>

    <!-- Create Ticket Button -->
    <div class="mb-3 text-end create-ticket-btn">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
        <i class="fa fa-plus"></i> Create Ticket
      </button>
    </div>

    <!-- Tickets Table -->
    <div class="tickets-card">
      <div class="table-responsive">
        <table class="table table-striped align-middle text-center mb-0" id="ticketsTable">
          <thead>
            <tr>
              <th>Ticket #</th>
              <th>Category</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Date Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Tickets will load dynamically via tickets.js -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Ticket Modal -->
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="createTicketForm">
        <div class="modal-header">
          <h5 class="modal-title" id="createTicketLabel">Create New Ticket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Category -->
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option value="Payment">Payment</option>
              <option value="Unit">Unit</option>
              <option value="Maintenance">Maintenance</option>
              <option value="Other" selected>Other</option>
            </select>
          </div>

          <!-- Subject -->
          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required placeholder="Enter subject...">
          </div>

          <!-- Message -->
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" rows="4" class="form-control" placeholder="Describe your concern..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="fa fa-paper-plane"></i> Submit Ticket
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>

<!-- External JS -->
<script src="../../asset/js/tickets.js"></script>

