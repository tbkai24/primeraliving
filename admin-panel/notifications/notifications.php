<?php
include '../layouts/header.php';

// Handle form submission for sending manual notification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form input for manual notification
    $title = $_POST['title'];
    $message = $_POST['message'];
    $user_id = $_POST['user_id']; // Assuming you want to send it to a specific user

    // Validate inputs
    if (!empty($title) && !empty($message) && !empty($user_id)) {
        // Simulate saving or sending the notification
        $success_msg = "Notification to user ID $user_id sent successfully with the title '$title'.";
    } else {
        $error_msg = "All fields are required.";
    }
}

// Dummy users for the dropdown (this can be replaced by actual data from the database)
$users = [
    (object) ['user_id' => 1, 'fullname' => 'Juan Dela Cruz'],
    (object) ['user_id' => 2, 'fullname' => 'Maria Santos'],
    (object) ['user_id' => 3, 'fullname' => 'Pedro Ramos']
];

// Dummy list of sent notifications (this can be replaced by actual data from the database)
$sent_notifications = [
    ['id' => 1, 'title' => 'Payment Reminder', 'message' => 'Reminder: Your payment is due in 3 days. Please make sure to settle your dues.'],
    ['id' => 2, 'title' => 'Lease Expiry Notice', 'message' => 'Your lease will expire in 30 days. Please contact us for renewal.'],
    ['id' => 3, 'title' => 'Maintenance Schedule', 'message' => 'Scheduled maintenance will take place on 2025-05-15. Please make necessary arrangements.']
];
?>

<div class="container">
    <h2 class="mb-4">Sent Notifications</h2>
    <p>List of notifications that have already been sent. You can send a custom reminder for any notification.</p>

    <?php if (isset($success_msg)) : ?>
        <div class="alert alert-success">
            <?= $success_msg ?>
        </div>
    <?php elseif (isset($error_msg)) : ?>
        <div class="alert alert-danger">
            <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <!-- Display List of Sent Notifications -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sent_notifications as $notification): ?>
                <tr>
                    <td><?= $notification['title'] ?></td>
                    <td><?= $notification['message'] ?></td>
                    <td>
                        <!-- View and Delete buttons -->
                        <a href="view_notification.php?id=<?= $notification['id'] ?>" class="btn btn-info btn-sm">View</a>
                        <a href="delete_notification.php?id=<?= $notification['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Button for sending a custom reminder, placed at the upper-right corner -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#sendReminderModal">Send Custom Reminder</button>
    </div>

    <!-- Modal for sending custom reminder -->
    <div class="modal fade" id="sendReminderModal" tabindex="-1" aria-labelledby="sendReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendReminderModalLabel">Send Custom Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="notifications.php">
                        <div class="mb-3">
                            <label for="reminder_title" class="form-label">Notification Title</label>
                            <input type="text" class="form-control" id="reminder_title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="reminder_message" class="form-label">Message</label>
                            <textarea class="form-control" id="reminder_message" name="message" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="reminder_user_id" class="form-label">Send To (User)</label>
                            <select class="form-select" id="reminder_user_id" name="user_id" required>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user->user_id ?>"><?= $user->fullname ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Send Reminder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS for modal functionality -->
<script>
    var reminderModal = document.getElementById('sendReminderModal')
    reminderModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget // Button that triggered the modal
        var title = button.getAttribute('data-title')
        var message = button.getAttribute('data-message')

        // Update the modal's content
        var modalTitle = reminderModal.querySelector('.modal-title')
        var modalMessage = reminderModal.querySelector('#reminder_message')
        var modalInputTitle = reminderModal.querySelector('#reminder_title')

        modalTitle.textContent = 'Send Custom Reminder: ' + title
        modalInputTitle.value = title
        modalMessage.value = message
    })
</script>

<?php include '../layouts/footer.php'; ?>
