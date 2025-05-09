<!-- footer.php -->
<footer class="text-white text-center py-4">
  <div class="container">
    <p class="mb-2">&copy; 2025 Commupay. All rights reserved.</p>
    <div>
      <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
      <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>
<!-- Bootstrap JS -->
<script src="<?php echo APPURL; ?>/asset/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle for Password
  const password = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");
  togglePassword.addEventListener("click", function () {
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
  });

  // Toggle for Confirm Password
  const confirmPassword = document.getElementById("confirm_password");
  const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
  toggleConfirmPassword.addEventListener("click", function () {
    const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
    confirmPassword.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
  });
   // Toggle for Login Password
   const toggleLoginPassword = document.getElementById('toggleLoginPassword');
  const passwordInput = document.getElementById('loginpassword');

  toggleLoginPassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
  });
</script>
<?php if (isset($_SESSION['admin'])): ?>
  </div> <!-- Close d-flex -->
<?php endif; ?>
</body>
</html>