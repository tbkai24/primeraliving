<?php
$page = 'users';
include '../layouts/header.php';
?>

<!-- ===== Alert Container ===== -->
<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<!-- ===== User Management Section ===== -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="admin-header"><i class="fas fa-users me-2"></i> User Management</h2>
  </div>

  <!-- ===== Filters (Compact Inline) ===== -->
  <div class="d-flex mb-3 gap-2 align-items-center flex-wrap">
    <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Search by name/email" style="max-width: 200px;">
    <select id="statusFilter" class="form-select form-select-sm" style="max-width: 130px;">
      <option value="all">All Status</option>
      <option value="active">Active</option>
      <option value="banned">Banned</option>
      <option value="deleted">Deleted</option>
    </select>
    <input type="date" id="fromDate" class="form-control form-control-sm" style="max-width: 130px;">
    <input type="date" id="toDate" class="form-control form-control-sm" style="max-width: 130px;">
    <button class="btn btn-secondary btn-sm" id="resetFilters">
      <i class="fas fa-undo"></i>
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Mobile Number</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Users will be dynamically loaded here via JS -->
      </tbody>
    </table>
  </div>
</div>

<!-- ===== Edit/View User Modal ===== -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editUserForm" class="modal-content">
      <input type="hidden" name="user_id">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserLabel">View / Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Full Name</label>
          <input type="text" class="form-control" name="fullname" readonly>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" readonly>
        </div>
        <div class="mb-3">
          <label>Mobile Number</label>
          <input type="text" class="form-control" name="mobile_number" readonly>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select class="form-select" name="status" required>
            <option value="active">Active</option>
            <option value="banned">Banned</option>
            <option value="deleted">Deleted</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Created At</label>
          <input type="text" class="form-control" name="created_at" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Status</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== JS ===== -->
<script src="../asset/js/user.js?v=<?= time() ?>"></script>

<?php include '../layouts/footer.php'; ?>
