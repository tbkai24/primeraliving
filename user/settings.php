<?php
include '../config/config.php';
include '../includes/header.php'; // This will automatically start the session

// Make sure the user is logged in
if (!isset($_SESSION['user'])) {
    echo "<div class='container mt-5 alert alert-danger'>Not logged in.</div>";
    include '../auth/login.php';
    exit;
}

// Get user data from session, with checks to prevent undefined index errors
$user = $_SESSION['user'];

// Ensure keys are set before accessing them
$fullname = isset($user['fullname']) ? $user['fullname'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$mobile_number = isset($user['mobile_number']) ? $user['mobile_number'] : '';

// Handle the form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile update
    if (isset($_POST['update_profile'])) {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $mobile_number = trim($_POST['mobile_number']);
        
        // Validate input
        if (empty($fullname) || empty($email) || empty($mobile_number)) {
            $error = "All fields are required.";
        } else {
            // Prepare the SQL update query for profile details
            $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email, mobile_number = :mobile_number WHERE user_id = :user_id");

            // Execute the query
            $stmt->execute([
                ':fullname' => $fullname,
                ':email' => $email,
                ':mobile_number' => $mobile_number,
                ':user_id' => $user['user_id'] // Update the logged-in user's data
            ]);

            // Update session data to reflect the changes
            $_SESSION['user']['fullname'] = $fullname;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['mobile_number'] = $mobile_number;

            // Success message
            $success = "Profile updated successfully.";
        }
    }

    // Password update
    if (isset($_POST['update_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate password fields
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All password fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirmation do not match.";
        } else {
            // Check if current password is correct
            $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $user['user_id']]);
            $db_user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($current_password, $db_user['password'])) {
                // Update the password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_password = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
                $update_password->execute([
                    ':password' => $hashed_password,
                    ':user_id' => $user['user_id']
                ]);

                // Success message for password update
                $success_password = "Password updated successfully.";
            } else {
                $error = "Current password is incorrect.";
            }
        }
    }
}
?>

<div class="container mt-5 text-dark" style="background-color: #EBE7DB;">
    <h3 class="mb-4">Account Settings</h3>

    <!-- Display error message if any -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Display success message for profile update -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Display success message for password update -->
    <?php if (isset($success_password)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_password); ?></div>
    <?php endif; ?>

    <!-- Settings Form for Profile Update -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" 
                   value="<?php echo htmlspecialchars($fullname); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" name="mobile_number" 
                   value="<?php echo htmlspecialchars($mobile_number); ?>" required>
        </div>

        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
    </form>

    <!-- Password Update Form -->
    <hr>
    <h3>Change Password</h3>

    <!-- Display error message for password -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Password Change Form -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" name="current_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" required>
        </div>

        <button type="submit" name="update_password" class="btn btn-primary mb-3">Update Password</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
