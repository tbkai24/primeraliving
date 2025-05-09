<?php
include '../config/config.php';
include '../includes/header.php';

// Sample data - replace with database queries
$transactions = [
  [
    'transaction_id' => 'TRX123456',
    'date' => '2025-04-10',
    'amount' => '₱12,000',
    'status' => 'Paid',
  ],
  [
    'transaction_id' => 'TRX123457',
    'date' => '2025-03-10',
    'amount' => '₱15,000',
    'status' => 'Pending',
  ],
  [
    'transaction_id' => 'TRX123458',
    'date' => '2025-02-10',
    'amount' => '₱10,000',
    'status' => 'Paid',
  ]
];
?>

<div class="container py-5">
  <h2 class="mb-4">Transaction History</h2>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead style="background-color: #7e57c2; color: white;">
        <tr>
          <th>Transaction ID</th>
          <th>Date</th>
          <th>Amount</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody style="background-color: #EBE7DB;">
        <?php foreach ($transactions as $transaction): ?>
          <tr>
            <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
            <td><?php echo htmlspecialchars($transaction['date']); ?></td>
            <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
            <td>
              <?php if ($transaction['status'] == 'Pending'): ?>
                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($transaction['status']); ?></span>
              <?php else: ?>
                <span class="badge bg-success"><?php echo htmlspecialchars($transaction['status']); ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
