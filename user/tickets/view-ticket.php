<?php
include '../../config/config.php';
include '../../includes/header.php';

// Dummy ticket data (replace with database lookup using $_GET['id'])
$ticketId = $_GET['id'] ?? 0;
$tickets = [
  1 => [
    'subject' => 'Request for gate remote replacement',
    'status' => 'Open',
    'created_at' => '2025-05-01',
    'description' => 'Hi, I lost my gate remote. Can I request a replacement?',
  ],
  2 => [
    'subject' => 'Noise complaint from neighbor',
    'status' => 'Closed',
    'created_at' => '2025-04-20',
    'description' => 'There has been consistent loud music past 10PM in Unit B2.',
  ],
];

$ticket = $tickets[$ticketId] ?? null;
?>

<div class="container py-5" style="min-height: 100vh;">
  <?php if ($ticket): ?>
    <h2>Ticket #<?php echo $ticketId; ?></h2>
    <div class="mb-3">
      <strong>Subject:</strong> <?php echo htmlspecialchars($ticket['subject']); ?>
    </div>
    <div class="mb-3">
      <strong>Status:</strong>
      <?php if ($ticket['status'] === 'Open'): ?>
        <span class="badge bg-warning text-dark">Open</span>
      <?php else: ?>
        <span class="badge bg-success">Closed</span>
      <?php endif; ?>
    </div>
    <div class="mb-3">
      <strong>Created On:</strong> <?php echo htmlspecialchars($ticket['created_at']); ?>
    </div>
    <div class="mb-4">
      <strong>Description:</strong>
      <p><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
    </div>
    <a href="tickets.php" class="btn btn-secondary">Back to Tickets</a>
  <?php else: ?>
    <div class="alert alert-danger">Ticket not found.</div>
    <a href="tickets.php" class="btn btn-secondary">Back to Tickets</a>
  <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
