<?php
include '../../config/config.php';
include '../layouts/header.php';

// Default credentials
$defaultEmail = "admin1@primeraliving.com";
$defaultPassword = "admin00";

// Check if the admin is already logged in
if (isset($_SESSION['admin'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Query the database to find the admin user by email
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Check if the password is hashed or plain text
            if (password_verify($password, $admin['password'])) {
                // Successful login: store admin session
                $_SESSION['admin'] = $admin;
                header('Location: ../index.php'); // Redirect to the admin dashboard after successful login
                exit();
            } elseif ($password === $admin['password']) {
                // The password is in plain text, hash it for future use
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Update the password in the database to be hashed
                $updateStmt = $pdo->prepare("UPDATE admins SET password = :password WHERE email = :email");
                $updateStmt->execute(['password' => $hashedPassword, 'email' => $email]);

                // Successful login: store admin session
                $_SESSION['admin'] = $admin;
                header('Location: ../index.php'); // Redirect to the admin dashboard after successful login
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}

// Only trigger auto login if no POST request has been made
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_SESSION['admin'])) {
    $_POST['email'] = $defaultEmail;
    $_POST['password'] = $defaultPassword;
    $_SERVER['REQUEST_METHOD'] = 'POST';  // Trigger POST request logic
    // Do not redirect again, just process the form submission
}
?>

<!-- Admin Login Form -->
<section style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="bg-white shadow p-5 rounded" style="max-width: 350px; width: 100%;">
        <h2 class="text-center mb-4 text-dark fs-5">Admin Login</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-bold">Email Address</label>
                <div class="position-relative">
                    <input type="email" name="email" class="form-control ps-5" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <i class="fas fa-envelope position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
            </div>

            <div class="mb-2 text-dark">
                <label class="form-label fw-bold">Password</label>
                <div class="position-relative">
                    <input type="password" id="password" name="password" class="form-control ps-5 pe-5" required value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>">
                    <i class="fas fa-lock position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
                </div>
            </div>

            <button type="submit" class="btn w-100 mt-2" style="background-color: #7e57c2; color: white;">Login</button>
        </form>
    </div>
</section>

<?php include '../layouts/footer.php'; ?>
