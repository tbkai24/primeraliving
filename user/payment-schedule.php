<?php
include '../config/config.php';
include '../includes/header.php';

// Sample data - replace with database queries
$payments = [
  [
    'unit' => 'Unit A1 - Tower 1',
    'due_date' => 'May 15, 2025',
    'amount_due' => '₱12,000',
    'status' => 'Pending',
  ],
  [
    'unit' => 'Unit A1 - Tower 1',
    'due_date' => 'April 15, 2025',
    'amount_due' => '₱12,000',
    'status' => 'Paid',
  ]
];
?>

<div class="container py-5">
  <h2 class="mb-4">Payment Schedule</h2>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead style="background-color: #7e57c2; color: white;">
        <tr>
          <th>Rental Unit</th>
          <th>Due Date</th>
          <th>Amount Due</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody style="background-color: #EBE7DB;">
        <?php foreach ($payments as $payment): ?>
          <tr>
            <td><?php echo htmlspecialchars($payment['unit']); ?></td>
            <td><?php echo htmlspecialchars($payment['due_date']); ?></td>
            <td><?php echo htmlspecialchars($payment['amount_due']); ?></td>
            <td>
              <?php if ($payment['status'] == 'Pending'): ?>
                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($payment['status']); ?></span>
              <?php else: ?>
                <span class="badge bg-success"><?php echo htmlspecialchars($payment['status']); ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($payment['status'] == 'Pending'): ?>
                <a href="#" class="btn btn-sm"style="background-color: #7e57c2; color: white;">Pay Now</a>
              <?php else: ?>
                <a href="#" class="btn btn-sm btn-secondary disabled">Paid</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
