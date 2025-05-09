<?php
include '../layouts/header.php';
// Assuming you have a database connection set up
// require "../../config/config.php";

// Dummy data for tickets (for prototype)
$tickets = [
  (object)[
    'ticket_id' => 1,
    'user_id' => 101,
    'subject' => 'Issue with Property Maintenance',
    'status' => 'open',
    'created_at' => '2025-05-01 12:30:00',
    'message' => 'There is a leaking pipe in the kitchen.'
  ],
  (object)[
    'ticket_id' => 2,
    'user_id' => 102,
    'subject' => 'Request for Repair',
    'status' => 'closed',
    'created_at' => '2025-04-28 09:20:00',
    'message' => 'The air conditioning system is not working properly.'
  ],
  (object)[
    'ticket_id' => 3,
    'user_id' => 103,
    'subject' => 'Water Supply Issue',
    'status' => 'open',
    'created_at' => '2025-05-05 08:10:00',
    'message' => 'Water supply is intermittent.'
  ]
];
?>

<div class="container-fluid">
  <h2 class="mb-4">Support Tickets</h2>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Ticket ID</th>
          <th>User ID</th>
          <th>Subject</th>
          <th>Status</th>
          <th>Created At</th>
          <th>Message</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tickets as $ticket): ?>
          <tr>
            <td><?= $ticket->ticket_id ?></td>
            <td><?= $ticket->user_id ?></td>
            <td><?= htmlspecialchars($ticket->subject) ?></td>
            <td>
              <span class="badge bg-<?= $ticket->status === 'open' ? 'warning' : 'success' ?>">
                <?= ucfirst($ticket->status) ?>
              </span>
            </td>
            <td><?= $ticket->created_at ?></td>
            <td><?= nl2br(htmlspecialchars($ticket->message)) ?></td>
            <td>
              <a href="view_ticket.php?ticket_id=<?= $ticket->ticket_id ?>" class="btn btn-sm" style="background-color: #7e57c2; color: white;">View</a>
              <?php if ($ticket->status === 'open'): ?>
                <a href="close_ticket.php?ticket_id=<?= $ticket->ticket_id ?>" class="btn btn-sm btn-danger">Close</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
