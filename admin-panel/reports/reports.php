<?php include '../layouts/header.php'; ?>

<div class="col-md-9 col-lg-10 main-content-area">
  <div class="page-header mb-4">
    <h2><i class="fas fa-chart-bar me-2 text-primary"></i>Reports & Analytics</h2>
  </div>

  <!-- Summary Cards -->
  <div class="row g-4 mb-4 fade-in" id="summaryCards">
    <div class="col-md-4">
      <div class="card summary-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Current Month Revenue</h6>
              <h3 class="fw-bold text-success mb-0" id="currentRevenue">₱0</h3>
            </div>
            <i class="fas fa-chart-line fa-2x text-success"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card summary-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Late Payments</h6>
              <h3 class="fw-bold text-warning mb-0" id="latePayments">0</h3>
            </div>
            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card summary-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-1">Expiring Leases</h6>
              <h3 class="fw-bold text-primary mb-0" id="expiringLeases">0</h3>
            </div>
            <i class="fas fa-calendar-times fa-2x text-primary"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart -->
  <div class="card chart-card shadow-sm fade-in-up">
    <div class="card-body">
      <h4 class="card-title text-primary mb-4">
        <i class="fas fa-chart-area me-2"></i>Monthly Revenue
      </h4>
      <canvas id="revenueChart" height="100"></canvas>
    </div>
  </div>

  <!-- Export PDF -->
  <div class="text-end mt-4 fade-in-up">
    <button class="btn btn-gradient-primary" id="exportPDF">
      <i class="fas fa-file-export me-2"></i> Export PDF
    </button>
  </div>

  <!-- Optional Table for Details -->
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title">Transactions</h5>
      <table class="table table-bordered" id="reportTable">
        <thead class="table">
          <tr>
            <th>ID</th>
            <th>Tenant</th>
            <th>Invoice</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Method</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

<!-- Chart.js & jsPDF -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="../asset/js/reports.js"></script>

<?php include '../layouts/footer.php'; ?>
