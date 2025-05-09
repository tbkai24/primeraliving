<?php
include '../config/config.php';
include '../includes/header.php';
if (isset($_SESSION['user'])) {
  header('Location: ../index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user; 
            header('Location: ../index.php');
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!-- Login Form -->
<section class="d-flex align-items-center justify-content-center">
  <div class="container bg-white shadow p-5 rounded" style="max-width: 350px;">
    <h2 class="text-center mb-4 text-dark fs-5">Login to Primera Living</h2>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label fw-bold">Email Address</label>
        <div class="position-relative">
          <input type="email" name="email" class="form-control ps-5" required>
          <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        </div>
      </div>

      <div class="mb-2 text-dark">
        <label class="form-label fw-bold">Password</label>
        <div class="position-relative">
          <input type="password" id="password" name="password" class="form-control ps-5 pe-5" required>
          <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
        </div>
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe">
          <label class="form-check-label text-dark" for="rememberMe">Remember Me</label>
        </div>
      </div>

      <div class="mb-3 text-end">
        <a href="forgot_password.php" class="text-decoration-none small text-muted">Forgot Password?</a>
      </div>

      <button type="submit" class="btn w-100 mt-2" style="background-color: #7e57c2; color: white;">Login</button>
    </form>

    <div class="text-center mt-3 text-dark">
      <a href="register.php" class="text-dark">Don't have an account? Register</a>
    </div>
  </div>
</section>






<?php include '../includes/footer.php'; ?>
