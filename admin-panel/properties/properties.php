<?php
$page = 'properties';
include '../layouts/header.php';
?>

<!-- ===== Property Management Section ===== -->
<div class="container-fluid">

  <!-- ===== Alert Box Above Filters ===== -->
  <div id="alert-container" class="mb-3"></div>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="property-header"><i class="fas fa-building me-2"></i> Property Management</h2>
    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addPropertyModal">
      <i class="fas fa-plus"></i> Add Property
    </button>
  </div>

  <!-- Filters -->
  <div class="d-flex mb-3 gap-2 align-items-center flex-wrap">
    <input type="text" id="propertySearch" class="form-control form-control-sm" placeholder="Search by unit/address" style="max-width: 200px;">
    <select id="statusFilter" class="form-select form-select-sm" style="max-width: 130px;">
      <option value="all">All Status</option>
      <option value="available">Available</option>
      <option value="occupied">Occupied</option>
    </select>
    <input type="date" id="fromDate" class="form-control form-control-sm" style="max-width: 130px;">
    <input type="date" id="toDate" class="form-control form-control-sm" style="max-width: 130px;">
    <button class="btn btn-secondary btn-sm" id="resetFilters">
      <i class="fas fa-undo"></i>
    </button>
  </div>

  <!-- Properties Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Unit Name</th>
          <th>Description</th>
          <th>Image</th>
          <th>Address</th>
          <th>Rent (₱)</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Properties will be dynamically loaded via JS -->
      </tbody>
    </table>
  </div>
</div>

<!-- ===== Add Property Modal ===== -->
<div class="modal fade" id="addPropertyModal" tabindex="-1" aria-labelledby="addPropertyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addPropertyForm" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="addPropertyLabel">Add Property</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Unit Name</label>
          <input type="text" class="form-control" name="unit_name" required>
        </div>
        <div class="mb-3">
          <label>Description</label>
          <textarea class="form-control" name="description" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label>Property Image</label>
          <input type="file" class="form-control" name="image" accept="image/*">
        </div>
        <div class="mb-3">
          <label>Address</label>
          <input type="text" class="form-control" name="address" required>
        </div>
        <div class="mb-3">
          <label>Rent Amount</label>
          <input type="number" class="form-control" name="rent_amount" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select class="form-select" name="availability_status" required>
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Property</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== Edit Property Modal ===== -->
<div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editPropertyForm" class="modal-content" enctype="multipart/form-data">
      <input type="hidden" name="property_id">
      <div class="modal-header">
        <h5 class="modal-title" id="editPropertyLabel">Edit Property</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Unit Name</label>
          <input type="text" class="form-control" name="unit_name" required>
        </div>
        <div class="mb-3">
          <label>Description</label>
          <textarea class="form-control" name="description" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label>Address</label>
          <input type="text" class="form-control" name="address" required>
        </div>
        <div class="mb-3">
          <label>Rent Amount</label>
          <input type="number" class="form-control" name="rent_amount" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select class="form-select" name="availability_status" required>
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Current Image</label>
          <div id="currentImageContainer">
            <img id="currentImage" src="" style="width:120px;height:90px;object-fit:cover;border-radius:5px;">
          </div>
        </div>
        <div class="mb-3">
          <label>Change Image</label>
          <input type="file" class="form-control" name="image" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- JS -->
<script src="../asset/js/properties.js?v=<?= time() ?>"></script>
<?php include '../layouts/footer.php'; ?>
