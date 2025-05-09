<?php
include '../../config/config.php';
include '../../includes/header.php';

// Sample ticket data - replace with database query later
$tickets = [
  [
    'id' => 1,
    'subject' => 'Request for gate remote replacement',
    'status' => 'Open',
    'created_at' => '2025-05-01',
  ],
  [
    'id' => 2,
    'subject' => 'Noise complaint from neighbor',
    'status' => 'Closed',
    'created_at' => '2025-04-20',
  ],
];
?>

<div class="container py-5">
  <h2 class="mb-4">Support Tickets</h2>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead style="background-color: #7e57c2; color: white;">
        <tr>
          <th>ID</th>
          <th>Subject</th>
          <th>Status</th>
          <th>Date Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody style="background-color: #EBE7DB;">
        <?php foreach ($tickets as $ticket): ?>
          <tr>
            <td><?php echo htmlspecialchars($ticket['id']); ?></td>
            <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
            <td>
              <?php if ($ticket['status'] === 'Open'): ?>
                <span class="badge bg-warning text-dark">Open</span>
              <?php else: ?>
                <span class="badge bg-success">Closed</span>
              <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
            <td>
              <a href="view-ticket.php?id=<?php echo $ticket['id']; ?>" class="btn btn-sm" style="background-color: #7e57c2; color: white;">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Button below table -->
  <div class="mt-3">
    <a href="create-ticket.php" class="btn" style="background-color: #7e57c2; color: white;">Create New Ticket</a>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
