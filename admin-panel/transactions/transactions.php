<?php
$page = 'transactions';
include '../layouts/header.php';

require_once "../config/config.php";
require_once "../config/CRUD.php";

// Initialize CRUD
$crud = new CRUD($pdo);

// Optional: Get archived transactions count
$allTransactions = $crud->getAllTransactions();
$archivedCount = count(array_filter($allTransactions, fn($t) => $t['status'] === 'Archived'));
?>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-credit-card me-2"></i>Transactions</h2>
    <button id="toggleArchived" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-archive me-1"></i> Show Archived 
      <span id="archivedCount" class="badge bg-secondary ms-1"><?php echo $archivedCount; ?></span>
    </button>
  </div>

  <!-- Alert container -->
  <div id="alert-container"></div>

  <!-- Date Filters -->
  <div class="d-flex mb-3 gap-2 align-items-center flex-wrap">
    <input type="date" id="fromDate" class="form-control form-control-sm" style="max-width: 150px;" placeholder="From">
    <input type="date" id="toDate" class="form-control form-control-sm" style="max-width: 150px;" placeholder="To">
    <button class="btn btn-secondary btn-sm" id="resetFilters"><i class="fas fa-undo"></i></button>
  </div>

  <!-- Transactions Table -->
  <div class="table-responsive">
    <table class="table align-middle table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Description</th>
          <th>User ID</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Method</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- ===== View Transaction Modal ===== -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-file-invoice me-2"></i>Transaction Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>ID:</strong> <span id="t_id"></span></li>
          <li class="list-group-item"><strong>User ID:</strong> <span id="t_user"></span></li>
          <li class="list-group-item"><strong>Tenant Email:</strong> <span id="t_email"></span></li>
          <li class="list-group-item"><strong>Description:</strong> <span id="t_desc"></span></li>
          <li class="list-group-item"><strong>Amount:</strong> ₱<span id="t_amount"></span></li>
          <li class="list-group-item"><strong>Status:</strong> <span id="t_status"></span></li>
          <li class="list-group-item"><strong>Method:</strong> <span id="t_method"></span></li>
          <li class="list-group-item"><strong>Property:</strong> <span id="t_property"></span></li>
          <li class="list-group-item"><strong>Created At:</strong> <span id="t_created"></span></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JS for transactions -->
<script src="../asset/js/transactions.js"></script>

<?php include '../layouts/footer.php'; ?>
