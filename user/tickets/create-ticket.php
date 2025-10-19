<?php
include '../../includes/header.php';
?>

<div class="container my-4" style="min-height:80vh;">
    <h2 class="mb-4"><i class="fas fa-plus me-2"></i>Create New Support Ticket</h2>

    <!-- Alert Box -->
    <div id="alertBox"></div>

    <form id="createTicketForm">
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category" id="category" class="form-select" required>
                <option value="Payment">Payment</option>
                <option value="Unit">Unit</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Other" selected>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject..." required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" rows="6" class="form-control" placeholder="Describe your concern..." required></textarea>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i>Submit Ticket</button>
        <a href="tickets.php" class="btn btn-secondary ms-2">Back to My Tickets</a>
    </form>
</div>

<script src="../../asset/js/tickets.js?v=<?= time() ?>"></script>
<?php include '../../includes/footer.php'; ?>
