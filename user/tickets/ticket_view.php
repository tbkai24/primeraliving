<?php

include '../../includes/header.php';

$crud = new CRUD($pdo);

$ticket_id = $_GET['id'] ?? 0;
$ticket = $crud->getTicketById($ticket_id);

if (!$ticket) {
    echo "<div class='alert alert-danger my-4'>Ticket not found.</div>";
    exit;
}
?>

<div class="container my-4" style="min-height:80vh;">
    <a href="tickets.php" class="btn btn-secondary mb-3">&larr; Back to My Tickets</a>

    <h3>Ticket: <?= htmlspecialchars($ticket['subject']) ?></h3>
    <p>Category: <strong><?= htmlspecialchars($ticket['category']) ?></strong></p>
    <p>Status: 
        <span class="badge bg-<?= $ticket['status'] === 'Pending' ? 'warning text-dark' : 'success' ?>">
            <?= $ticket['status'] ?>
        </span>
    </p>

    <!-- Alert Box -->
    <div id="alertBox"></div>

    <!-- Conversation Box -->
    <div id="messages" class="border rounded p-3 mb-3" style="height:400px; overflow-y:auto; background:#f8f9fa;">
        <!-- Messages will be loaded dynamically via JS -->
    </div>

    <!-- Reply Form -->
    <form id="replyForm">
        <input type="hidden" name="action" value="add_reply">
        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
        <input type="hidden" name="sender" value="user">
        <input type="hidden" name="sender_name" value="You">

        <div class="mb-3">
            <textarea name="message" class="form-control" rows="4" placeholder="Type your reply..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Reply</button>
    </form>
</div>

<script src="../../asset/js/tickets.js?v=<?= time() ?>"></script>
<?php include '../../includes/footer.php'; ?>
