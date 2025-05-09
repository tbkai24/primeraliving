<?php include '../layouts/header.php'; ?>

<?php
// Dummy data for due schedules
$schedules = [
  [
    'fullname' => 'Ana Santos',
    'unit' => 'Unit 101 - Lavender',
    'due_date' => '2025-05-10',
    'amount' => 8500,
    'status' => 'pending'
  ],
  [
    'fullname' => 'Leo Garcia',
    'unit' => 'Unit 302 - Rosewood',
    'due_date' => '2025-05-12',
    'amount' => 12000,
    'status' => 'pending'
  ],
  [
    'fullname' => 'Mia Cruz',
    'unit' => 'Unit A2 - Garden View',
    'due_date' => '2025-05-15',
    'amount' => 10000,
    'status' => 'paid'
  ],
];

// Add days left calculation
foreach ($schedules as &$schedule) {
  $due = new DateTime($schedule['due_date']);
  $today = new DateTime();
  $interval = $today->diff($due);
  $schedule['days_left'] = (int)$interval->format('%r%a');
}
unset($schedule); // Break reference
?>

<div class="container-fluid">
  <h2 class="mb-4">Upcoming Payment Schedule</h2>

  <div class="table-responsive">
    <table class="table table-bordered align-middle table-striped">
      <thead class="table-dark">
        <tr>
          <th>Full Name</th>
          <th>Unit</th>
          <th>Due Date</th>
          <th>Days Left</th>
          <th>Amount (₱)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($schedules as $schedule): ?>
          <tr>
            <td><?= $schedule['fullname'] ?></td>
            <td><?= $schedule['unit'] ?></td>
            <td><?= date('F d, Y', strtotime($schedule['due_date'])) ?></td>
            <td>
              <?php if ($schedule['days_left'] < 0): ?>
                <span class="text-danger fw-bold">Overdue (<?= abs($schedule['days_left']) ?> days)</span>
              <?php elseif ($schedule['days_left'] === 0): ?>
                <span class="text-warning fw-bold">Due Today</span>
              <?php else: ?>
                <?= $schedule['days_left'] ?> day<?= $schedule['days_left'] > 1 ? 's' : '' ?>
              <?php endif; ?>
            </td>
            <td><?= number_format($schedule['amount'], 2) ?></td>
            <td>
              <span class="badge bg-<?= $schedule['status'] === 'paid' ? 'success' : 'warning' ?>">
                <?= ucfirst($schedule['status']) ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
