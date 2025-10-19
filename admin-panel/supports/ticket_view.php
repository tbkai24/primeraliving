<?php
$page = 'support';
include '../layouts/header.php';
require_once '../config/config.php';
require_once '../config/crud.php';

$crud = new CRUD($pdo);
$ticket_id = $_GET['id'] ?? 0;
$ticket = $crud->getTicketById($ticket_id);
$messages = $crud->getTicketMessages($ticket_id);

if(!$ticket){
    echo "<div class='alert alert-danger'>Ticket not found.</div>";
    exit;
}

$sender = "admin";
$sender_name = "Admin";
?>

<div class="container my-4">
    <a href="supports.php" class="btn btn-secondary mb-3">&larr; Back to Tickets</a>

    <h3>Ticket: <?= htmlspecialchars($ticket['subject']) ?></h3>

    <!-- Status Dropdown -->
    <form id="statusForm" class="mb-3 d-flex align-items-center gap-2">
        <label for="statusSelect" class="mb-0">Status:</label>
        <select id="statusSelect" class="form-select w-auto" name="status">
            <option value="Pending" <?= $ticket['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Closed" <?= $ticket['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Update</button>
    </form>

    <!-- Alert Box -->
    <div id="alertBox"></div>

    <!-- Conversation -->
    <div id="messages" class="border rounded p-3 mb-3" style="height:400px; overflow-y:auto; background:#f8f9fa;">
        <?php foreach($messages as $msg): ?>
            <div class="mb-2">
                <strong><?= htmlspecialchars($msg['sender_name']) ?></strong> 
                <small class="text-muted"><?= date("M d, Y h:i A", strtotime($msg['created_at'])) ?></small>
                <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Reply Form -->
    <form id="replyForm">
        <input type="hidden" name="action" value="add_reply">
        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
        <input type="hidden" name="sender" value="<?= $sender ?>">
        <input type="hidden" name="sender_name" value="<?= $sender_name ?>">

        <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="Type your reply..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Reply</button>
    </form>
</div>

<script src="../asset/js/supports.js?v=<?= time() ?>"></script>
<?php include '../layouts/footer.php'; ?>
