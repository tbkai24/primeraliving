<?php
include '../config/config.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fullname = trim($_POST['fullname']);
  $email = trim($_POST['email']);
  $mobile = trim($_POST['mobile']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Check if passwords match
  if ($password !== $confirm_password) {
    echo "<div class='alert alert-danger text-center'>Passwords do not match.</div>";
  } else {
    // Check if email already exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetchColumn() > 0) {
      echo "<div class='alert alert-warning text-center'>You already have an account. <a href='login.php'>Login here</a>.</div>";
    } else {
      // Proceed with registration
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (fullname, email, mobile, password) VALUES (?, ?, ?, ?)");

      if ($stmt->execute([$fullname, $email, $mobile, $hashedPassword])) {
        echo "<div class='alert alert-success text-center'>You have successfully created an account!</div>";
        echo "<script>
          setTimeout(function() {
            window.location.href = 'login.php';
          }, 2000);
        </script>";
      } else {
        echo "<div class='alert alert-danger text-center'>Registration failed. Try again.</div>";
      }
    }
  }
}
?>

<!-- Registration Form -->
<section class="py-5">
  <div class="container" style="max-width: 500px;">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="bg-white p-4 rounded shadow-sm text-dark">
          <div class="text-center mb-4">
            <h2 class="fw-bold">Register</h2>
          </div>

          <form method="POST">
            <!-- Full Name -->
            <div class="mb-3">
              <label class="form-label fw-bold">Full Name</label>
              <div class="position-relative">
                <input type="text" name="fullname" class="form-control ps-5" required>
                <i class="fas fa-user position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label fw-bold">Email Address</label>
              <div class="position-relative">
                <input type="email" name="email" class="form-control ps-5" required>
                <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              </div>
            </div>

            <!-- Mobile -->
            <div class="mb-3">
              <label class="form-label fw-bold">Mobile Number</label>
              <div class="position-relative">
                <input type="text" name="mobile" class="form-control ps-5" required>
                <i class="fas fa-phone position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label fw-bold">Password</label>
              <div class="position-relative">
                <input type="password" id="password" name="password" class="form-control ps-5 pe-5" required>
                <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
              <label class="form-label fw-bold">Confirm Password</label>
              <div class="position-relative">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control ps-5 pe-5" required>
                <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="toggleConfirmPassword" style="cursor: pointer;"></i>
              </div>
            </div>
          <div class="d-grid">
              <button type="submit" class="btn" style="background-color: #7e57c2; color: white;">Register</button>
            </div>
            <div class="text-center mt-3 text-dark">
      <a href="login.php" class="text-dark">You already have an account. Login</a>
    </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include '../includes/footer.php'; ?>
