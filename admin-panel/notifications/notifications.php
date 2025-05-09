<?php
include '../layouts/header.php';

// Handle form submission for sending notification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form input
    $title = $_POST['title'];
    $message = $_POST['message'];
    $user_id = $_POST['user_id']; // Assuming you want to send it to a specific user

    // Validate inputs
    if (!empty($title) && !empty($message) && !empty($user_id)) {
        // For now, just display a success message (simulate sending)
        $success_msg = "Notification to user ID $user_id sent successfully with the title '$title'.";
    } else {
        $error_msg = "All fields are required.";
    }
}

// Dummy users for the dropdown (without database)
$users = [
    (object) ['user_id' => 1, 'fullname' => 'Juan Dela Cruz'],
    (object) ['user_id' => 2, 'fullname' => 'Maria Santos'],
    (object) ['user_id' => 3, 'fullname' => 'Pedro Ramos']
];
?>

<div class="container">
    <h2 class="mb-4">Send Notification</h2>

    <?php if (isset($success_msg)) : ?>
        <div class="alert alert-success">
            <?= $success_msg ?>
        </div>
    <?php elseif (isset($error_msg)) : ?>
        <div class="alert alert-danger">
            <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="notifications.php">
        <div class="mb-3">
            <label for="title" class="form-label">Notification Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Send To (User)</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->user_id ?>"><?= $user->fullname ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn"style="background-color: #7e57c2; color: white;">Send Notification</button>
    </form>
</div>

<?php include '../layouts/footer.php'; ?>
