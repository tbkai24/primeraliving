<?php
include '../config/config.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Here, you would typically send an email with a password reset link.
            // Example: Generate a unique token and send a reset link to the user's email

            // For demonstration, we assume the link is generated and the email is sent.
            // In a real system, you'd use a proper mail function or service (e.g., PHP's mail(), SMTP, etc.).

            $reset_link = "http://example.com/reset_password.php?token=UNIQUE_RESET_TOKEN"; // Replace with actual token generation
            $message = "Click the following link to reset your password: $reset_link";
            // mail($email, "Password Reset Request", $message);

            // Show success message
            $success = "A password reset link has been sent to your email address.";
        } else {
            $error = "No account found with that email address.";
        }
    }
}
?>

<!-- Forgot Password Form -->
<section class="d-flex align-items-center justify-content-center">
  <div class="container bg-white shadow p-5 rounded" style="max-width: 350px;">
    <h2 class="text-center mb-4 text-dark fs-5">Forgot Password</h2>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label fw-bold">Email Address</label>
        <div class="position-relative">
          <input type="email" name="email" class="form-control ps-5" required>
          <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        </div>
      </div>

      <button type="submit" class="btn w-100 mt-2" style="background-color: #7e57c2; color: white;">Send Reset Link</button>
    </form>

    <div class="text-center mt-3 text-dark">
      <a href="login.php" class="text-dark">Back to Login</a>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
