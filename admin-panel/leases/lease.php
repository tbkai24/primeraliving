lease.php
<?php 
$page = 'leases'; 
include '../layouts/header.php'; 
require_once '../config/config.php'; // DB + SMTP configs
?>

<!-- ===== Lease Management Page ===== -->
<div class="container-fluid py-4">
  <!-- Header section with title + reminder icon -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-contract me-2"></i> Active Leases</h2>
    <button id="notifyLeases" class="btn btn-outline-primary btn-sm" title="Send All Due Reminders">
      <i class="fas fa-bell"></i>
    </button>
  </div>

  <!-- Alert container -->
  <div id="alert-container"></div>

  <!-- Filters (customized for Leases) -->
  <div class="d-flex mb-3 gap-2 align-items-center flex-wrap">
    <input type="text" id="tenantSearch" class="form-control form-control-sm" placeholder="Search tenant/unit" style="max-width: 200px;">

    <select id="leaseStatusFilter" class="form-select form-select-sm" style="max-width: 150px;">
      <option value="all">All Status</option>
      <option value="active">Active</option>
      <option value="due-soon">Due Soon (≤7 days)</option>
      <option value="overdue">Overdue</option>
      <option value="paid">Paid</option>
    </select>

    <button class="btn btn-secondary btn-sm" id="resetFilters" title="Reset filters">
      <i class="fas fa-undo"></i>
    </button>
  </div>

  <!-- Lease table -->
  <div class="table-responsive mt-3">
    <table class="table table-hover align-middle" id="leaseTable">
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Unit</th>
          <th>Lease Start</th>
          <th>Next Due</th>
          <th>Days Left</th>
          <th>Monthly Rent (₱)</th>
          <th>Status</th>
          <th>Notify</th>
        </tr>
      </thead>
      <tbody id="leaseBody">
        <tr><td colspan="8" class="text-center text-muted">Loading leases...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Lease-specific JavaScript -->
<script src="../asset/js/lease.js"></script>

<?php include '../layouts/footer.php'; ?>
