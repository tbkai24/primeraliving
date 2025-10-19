<?php
$page = 'admins';
include '../layouts/header.php';
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="admin-header"><i class="fas fa-user-shield me-2"></i> Admin Management</h2>
    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addAdminModal">
      <i class="fas fa-plus"></i> Add Admin
    </button>
  </div>

  <!-- Filters -->
  <div class="d-flex mb-3 gap-2 align-items-center flex-wrap">
    <input type="text" id="adminSearch" class="form-control form-control-sm" placeholder="Search by name/email" style="max-width: 200px;">
    <select id="roleFilter" class="form-select form-select-sm" style="max-width: 130px;">
      <option value="all">All Roles</option>
      <option value="admin">Admin</option>
      <option value="superadmin">Superadmin</option>
    </select>
    <button class="btn btn-secondary btn-sm" id="resetFilters">
      <i class="fas fa-undo"></i>
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addAdminForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Full Name</label>
          <input type="text" class="form-control" name="fullname" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select class="form-select" name="role" required>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add Admin</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editAdminForm" class="modal-content">
      <input type="hidden" name="admin_id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Full Name</label>
          <input type="text" class="form-control" name="fullname" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label>Password <small class="text-muted">(Leave blank to keep current)</small></label>
          <input type="password" class="form-control" name="password">
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select class="form-select" name="role" required>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="../asset/js/admin.js"></script>
<?php include '../layouts/footer.php'; ?>
