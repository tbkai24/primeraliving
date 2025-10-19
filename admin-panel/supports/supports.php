<?php
$page = 'supports';
include '../layouts/header.php';
?>

<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-life-ring me-2"></i> Support Tickets</h2>

    <!-- Filters -->
    <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
        <input type="text" id="filterKeyword" class="form-control form-control-sm" placeholder="Search subject/message" style="max-width:200px;">
        <select id="filterStatus" class="form-select form-select-sm" style="max-width:130px;">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="Closed">Closed</option>
        </select>
        <select id="filterCategory" class="form-select form-select-sm" style="max-width:130px;">
            <option value="">All Categories</option>
        </select>
        <button class="btn btn-secondary btn-sm" id="resetFilters"><i class="fas fa-undo"></i></button>
    </div>

    <!-- Alert Box -->
    <div id="alertBox"></div>

    <!-- Tickets Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="ticketsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ticketList">
                <!-- Tickets loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<script src="../asset/js/supports.js?v=<?= time() ?>"></script>
<?php include '../layouts/footer.php'; ?>
