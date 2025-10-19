<?php
include '../config/config.php';
include '../layouts/header.php'; // safe, sidebar hidden if not logged in

// ✅ Show message if user just logged in with default admin
if (isset($_SESSION['admin']) && isset($_SESSION['admin']['default']) && $_SESSION['admin']['default'] === true) {
    $_SESSION['default_admin_warning'] = "⚠️ You are logged in using the default admin credentials. For security, please create a new admin account and disable this one.";
}
?>

<section class="d-flex align-items-center justify-content-center position-relative" 
         style="background: url('../asset/images/login.jpg') no-repeat center center/cover; min-height: 100vh; padding: 80px 20px;">

  <!-- Dark overlay -->
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>

  <div class="container position-relative" style="max-width: 420px; z-index: 2;">
    <div class="bg-white shadow p-5 rounded">
      <h2 class="text-center mb-4 text-dark fs-5">Admin Login</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center mb-3" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['default_admin_warning'])): ?>
        <div class="alert alert-warning text-center mb-3" role="alert">
          <?= $_SESSION['default_admin_warning']; ?>
        </div>
        <?php unset($_SESSION['default_admin_warning']); ?>
      <?php endif; ?>

      <form method="POST" action="../handlers/auth_handler.php">
        <input type="hidden" name="action" value="login">

        <div class="mb-3">
          <label class="form-label fw-bold">Email Address</label>
          <div class="position-relative">
            <input type="email" name="email" class="form-control ps-5" placeholder="Enter your email" required 
                   value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
            <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Password</label>
          <div class="position-relative">
            <input type="password" name="password" class="form-control ps-5 pe-5" placeholder="Enter your password" required 
                   value="<?= htmlspecialchars($_POST['password'] ?? ''); ?>">
            <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>

        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Login</button>
      </form>
    </div>
  </div>
</section>

<?php include '../layouts/footer.php'; ?>
