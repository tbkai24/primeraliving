<?php

// Redirect if already logged in (must be before header output)
if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

// Include header after redirect check
include '../includes/header.php';
?>

<section class="d-flex align-items-center justify-content-center position-relative"
         style="background: url('../asset/images/login.jpg') no-repeat center center/cover; min-height: 100vh; padding: 80px 20px;">

  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>

  <div class="container position-relative" style="max-width: 420px; z-index:2;">
    <div class="bg-white shadow p-5 rounded">

      <h2 class="text-center mb-4 text-dark fs-5" id="formTitle">Login to Your Account</h2>

      <!-- Alert Messages -->
      <div id="alertBox"></div>

      <!-- ================= LOGIN FORM ================= -->
      <form id="loginForm" method="POST">

        <div class="mb-3">
          <label class="form-label fw-bold">Email Address</label>
          <div class="position-relative">
            <input type="email" name="email" id="email" class="form-control ps-5" placeholder="Enter your email" required>
            <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Password</label>
          <div class="position-relative">
            <input type="password" name="password" id="password" class="form-control ps-5 pe-5" placeholder="Enter your password" required>
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-lock"></i>
            </span>
            <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted toggle-password" style="cursor:pointer;">
              <i class="fas fa-eye"></i>
            </span>
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

        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha']['site']; ?>"></div>
        </div>

        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Login</button>
      </form>

      <!-- ================= VERIFY FORM ================= -->
      <form id="verifyForm" style="display:none;">
        <div class="mb-3">
          <label class="form-label fw-bold">Enter Verification Code</label>
          <div class="position-relative">
            <input type="text" name="vericode" id="vericode" class="form-control ps-5" placeholder="6-digit code" required>
            <i class="fas fa-key position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>
        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Verify & Continue</button>
        <div class="text-center mt-2">
          <small id="resendTimer" class="d-block mb-1 text-muted"></small>
          <a href="#" id="resendCode" class="text-primary small">Resend Code</a>
          <a href="#" id="showChangeEmailForm" class="text-primary small d-block mt-1">Change Email</a>
        </div>
      </form>

      <!-- ================= CHANGE EMAIL FORM ================= -->
      <form id="changeEmailForm" style="display:none;">
        <div class="mb-3">
          <label class="form-label fw-bold">New Email</label>
          <div class="position-relative">
            <input type="email" name="new_email" id="new_email" class="form-control ps-5" placeholder="Enter new email" required>
            <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>
        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Update Email</button>
        <div class="text-center mt-2">
          <a href="#" id="backToVerify" class="text-primary small">Back to Verification</a>
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="register.php" class="text-dark">Don't have an account? Register</a>
      </div>

    </div>
  </div>
</section>

<script src="../asset/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>
