<?php
include '../config/config.php';
include '../includes/header.php';

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>

<section class="d-flex align-items-center justify-content-center position-relative" 
         style="background: url('../asset/images/login.jpg') no-repeat center center/cover; min-height: 100vh; padding: 80px 20px;">

  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>

  <div class="container position-relative" style="max-width: 420px; z-index:2;">
    <div class="bg-white shadow p-5 rounded">

      <h2 class="text-center mb-4 text-dark fs-5" id="formTitle">Create Your Account</h2>

      <!-- Alert Messages -->
      <div id="alertBox"></div>

      <!-- ================= REGISTER FORM ================= -->
      <form id="registerForm" method="post">

        <!-- First Name -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">First Name</label>
          <div class="position-relative">
            <input type="text" name="first_name" id="first_name" class="form-control ps-5" placeholder="Enter your first name" required>
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-user"></i>
            </span>
          </div>
        </div>

        <!-- Last Name -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Last Name</label>
          <div class="position-relative">
            <input type="text" name="last_name" id="last_name" class="form-control ps-5" placeholder="Enter your last name" required>
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-user"></i>
            </span>
          </div>
        </div>

        <!-- Mobile Number -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Mobile Number</label>
          <div class="position-relative">
            <input type="tel" name="mobile_number" id="mobile_number" class="form-control ps-5" placeholder="Enter your mobile number" required pattern="[0-9]{11}">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-phone"></i>
            </span>
          </div>
          <small class="text-muted">Format: 09XXXXXXXXX</small>
        </div>

        <!-- Email -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Email</label>
          <div class="position-relative">
            <input type="email" name="email" id="email" class="form-control ps-5" placeholder="Enter your email" required>
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-envelope"></i>
            </span>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
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

          <!-- Strength Bar -->
          <div class="progress mt-2" style="height:5px;">
            <div id="strengthBar" class="progress-bar" role="progressbar" style="width:0%"></div>
          </div>
          <small id="strengthText" class="text-muted">Password strength</small>

          <!-- Password Requirements -->
          <ul class="mt-2 small text-muted" id="passwordRequirements">
            <li id="reqLength">At least 8 characters</li>
            <li id="reqUpper">At least 1 uppercase letter</li>
            <li id="reqLower">At least 1 lowercase letter</li>
            <li id="reqNumber">At least 1 number</li>
            <li id="reqSpecial">At least 1 special character</li>
          </ul>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3 position-relative">
          <label class="form-label fw-bold">Confirm Password</label>
          <div class="position-relative">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control ps-5 pe-5" placeholder="Confirm your password" required>
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
              <i class="fas fa-lock"></i>
            </span>
            <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted toggle-password" style="cursor:pointer;">
              <i class="fas fa-eye"></i>
            </span>
          </div>
          <small id="confirmText" class="text-muted"></small>
        </div>

        <!-- reCAPTCHA -->
        <div class="mb-3">
          <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha']['site']; ?>"></div>
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Register</button>
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
            <input type="email" name="new_email" id="new_email" class="form-control ps-5" placeholder="Enter new email">
            <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
          </div>
        </div>
        <button type="submit" class="btn w-100 mt-2" style="background-color:#2A6F9E; color:white;">Update Email</button>
        <div class="text-center mt-2">
          <a href="#" id="backToVerify" class="text-primary small">Back to Verification</a>
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="login.php" class="text-dark">Already have an account? Login</a>
      </div>

    </div>
  </div>
</section>

<script src="../asset/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>
