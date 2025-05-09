<?php
include '../layouts/header.php';

// Dummy data for transactions (replace with real data later)
$transactions = [
  (object)[
    'transaction_id' => 1,
    'user_id' => 101,
    'property_id' => 1,
    'amount' => 8500.00,
    'payment_date' => '2025-05-01',
    'status' => 'Completed',
    'created_at' => '2025-05-01 10:15:00'
  ],
  (object)[
    'transaction_id' => 2,
    'user_id' => 102,
    'property_id' => 2,
    'amount' => 12000.00,
    'payment_date' => '2025-04-27',
    'status' => 'Pending',
    'created_at' => '2025-04-27 09:30:00'
  ],
  (object)[
    'transaction_id' => 3,
    'user_id' => 103,
    'property_id' => 3,
    'amount' => 10000.00,
    'payment_date' => '2025-03-20',
    'status' => 'Completed',
    'created_at' => '2025-03-20 11:45:00'
  ]
];
?>

<!-- Heading for the page -->
<div class="container-fluid">
  <div class="mb-4">
    <h2>Transactions</h2>
  </div>

<!-- Table placed below the heading with additional margin-top -->
<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
      <tr>
        <th>Transaction ID</th>
        <th>User ID</th>
        <th>Property ID</th>
        <th>Amount (₱)</th>
        <th>Payment Date</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $transaction): ?>
        <tr>
          <td><?= $transaction->transaction_id ?></td>
          <td><?= $transaction->user_id ?></td>
          <td><?= $transaction->property_id ?></td>
          <td><?= number_format($transaction->amount, 2) ?></td>
          <td><?= $transaction->payment_date ?></td>
          <td>
            <span class="badge bg-<?= $transaction->status === 'Completed' ? 'success' : 'warning' ?>">
              <?= ucfirst($transaction->status) ?>
            </span>
          </td>
          <td><?= $transaction->created_at ?></td>
          <td>
            <a href="#" class="btn btn-sm" style="background-color: #7e57c2; color: white;">View</a>
            <a href="#" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../layouts/footer.php'; ?>
