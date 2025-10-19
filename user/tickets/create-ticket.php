<?php
include '../../config/config.php';
include '../../includes/header.php';

// Initialize variables
$subject = $description = '';
$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subject = trim($_POST['subject']);
  $description = trim($_POST['description']);

  // Basic validation
  if (empty($subject)) {
    $errors[] = "Subject is required.";
  }
  if (empty($description)) {
    $errors[] = "Description is required.";
  }

  if (empty($errors)) {
    // Replace this with actual database insert logic
    $success = true;

    // Clear form
    $subject = $description = '';
  }
}
?>

<div class="container py-5 mt-5" style="min-height: 100vh;">
  <h2 class="mb-4">Create New Support Ticket</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php elseif ($success): ?>
    <div class="alert alert-success">
      Ticket submitted successfully!
    </div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="mb-3">
      <label for="subject" class="form-label">Subject</label>
      <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="6" required><?php echo htmlspecialchars($description); ?></textarea>
    </div>

    <button type="submit" class="btn btn-action">Submit Ticket</button>
    <a href="tickets.php" class="btn" style="background-color: #dc3545; color: white;">Cancel</a>
  </form>
</div>

<?php include '../../includes/footer.php'; ?>
