<?php
include '../config/config.php';
include '../includes/header.php';

session_start();

// Make sure user email is passed (you can store email in session after registration)
$email = $_SESSION['email'] ?? null;

if (!$email) {
    // Redirect if email not set
    header("Location: register.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredCode = trim($_POST['vericode']);
    $enteredHash = hash('sha256', $enteredCode);

    // Check user with code and not yet verified
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND vericode_hash = ? AND verified = 0");
    $stmt->execute([$email, $enteredHash]);
    $user = $stmt->fetch();

    if ($user) {
        // Update user as verified
        $update = $pdo->prepare("UPDATE users SET verified = 1, vericode_hash = NULL WHERE user_id = ?");
        $update->execute([$user['user_id']]);
        $success = "✅ Your email has been verified! Redirecting to login...";
        // Optionally destroy session
        unset($_SESSION['email']);
        echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
    } else {
        $error = "❌ Invalid or expired verification code.";
    }
}
?>

<section class="d-flex align-items-center justify-content-center" 
         style="min-height: 100vh; background: url('../asset/images/login.jpg') no-repeat center center/cover;">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>

  <div class="container position-relative" style="max-width: 420px; z-index: 2; margin-top: 80px; margin-bottom: 60px;">
    <div class="bg-white shadow p-5 rounded">
      <h2 class="text-center mb-4 text-dark fs-5">Verify Your Email</h2>

      <?php if (!empty($error)) : ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
      <?php elseif (!empty($success)) : ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-bold">Enter Verification Code</label>
          <input type="text" name="vericode" class="form-control" required>
        </div>

        <button type="submit" class="btn w-100 mt-2" style="background-color: #2A6F9E; color: white;">Verify</button>
      </form>

      <div class="text-center mt-3">
        <a href="login.php" class="text-dark">Back to Login</a>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
