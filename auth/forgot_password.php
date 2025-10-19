<?php
include '../config/config.php';
include '../includes/header.php';

if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>

<section class="d-flex align-items-center justify-content-center position-relative" 
         style="background: url('../asset/images/login.jpg') no-repeat center center/cover; min-height: 100vh; padding: 80px 20px;">

  <!-- Overlay -->
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>

  <div class="container position-relative" style="max-width: 420px; z-index:2;">
    <div class="bg-white shadow p-5 rounded">

      <h2 class="text-center mb-4 text-dark fs-5" id="formTitle">Forgot Password</h2>

      <!-- Alert Box -->
      <div id="alertBox"></div>

      <!-- ================= ENTER EMAIL FORM ================= -->
      <form id="forgotEmailForm">
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Email Address</label>
          <input type="email" name="email" id="email" class="form-control ps-5" placeholder="Enter your registered email" required>
          <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        </div>
        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Send Verification Code</button>
      </form>

      <!-- ================= VERIFY CODE FORM ================= -->
      <form id="fpVerifyForm" style="display:none;">
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Verification Code</label>
          <input type="text" name="vericode" id="vericode" class="form-control ps-5" placeholder="Enter 6-digit code" required>
          <i class="fas fa-key position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
        </div>
        <div class="text-center mt-2">
          <a href="#" id="resendCode" class="text-primary small">Resend Code</a>
          <span id="resendTimer" class="text-muted small ms-2"></span>
        </div>
        <button type="submit" class="btn w-100 mt-3" style="background-color:#2A6F9E; color:white;">Verify Code</button>
      </form>

      <!-- ================= NEW PASSWORD FORM ================= -->
      <form id="fpNewPasswordForm" style="display:none;">
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">New Password</label>
          <input type="password" id="password" name="password" class="form-control ps-5 pe-5" placeholder="Enter new password" required>
          <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor:pointer;"></i>
        </div>

        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control ps-5 pe-5" placeholder="Confirm your password" required>
          <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="toggleConfirmPassword" style="cursor:pointer;"></i>
          <small id="confirmText" class="d-block mt-1"></small>
        </div>

        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Set New Password</button>
      </form>

      <div class="text-center mt-3">
        <a href="login.php" class="text-dark">Back to Login</a>
      </div>

    </div>
  </div>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script src="../asset/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>
