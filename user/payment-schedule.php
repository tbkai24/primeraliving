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

<div class="payment-bg">
  <div class="container">
    <h2 class="mb-4 payment-title"><i class="fas fa-calendar-alt me-2"></i>My Payment Schedule</h2>

    <div class="table-responsive payment-card p-3">
      <table class="table table-bordered table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>Rental Unit</th>
            <th>Due Date</th>
            <th>Amount Due</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($payments as $payment): ?>
            <tr class="fade-row">
              <td><?= htmlspecialchars($payment['unit']); ?></td>
              <td><?= htmlspecialchars($payment['due_date']); ?></td>
              <td><?= htmlspecialchars($payment['amount_due']); ?></td>
              <td>
                <?php if ($payment['status'] == 'Pending'): ?>
                  <span class="badge bg-warning text-dark"><?= htmlspecialchars($payment['status']); ?></span>
                <?php else: ?>
                  <span class="badge bg-success"><?= htmlspecialchars($payment['status']); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($payment['status'] == 'Pending'): ?>
                  <a href="payment.php?unit=<?= urlencode($payment['unit']); ?>&amount=<?= urlencode($payment['amount_due']); ?>&due_date=<?= urlencode($payment['due_date']); ?>" 
                     class="btn btn-sm btn-action">
                    Pay Now
                  </a>
                <?php else: ?>
                  <button class="btn btn-sm btn-close-custom" disabled>Paid</button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
