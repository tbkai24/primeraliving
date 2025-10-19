<?php
session_start();
include '../../config/config.php'; // Adjust if needed

// ✅ Alias $pdo as $conn for compatibility
$conn = $pdo;

// Default admin credentials (for first-time login)
$defaultAdmin = [
    'email' => 'admin@primeraliving.com',
    'password' => 'admin00'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // ✅ Check if there's an admin in DB
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // Admin found in database
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin'] = [
                    'id' => $admin['id'],
                    'email' => $admin['email'],
                    'role' => $admin['role'] ?? 'admin'
                ];
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error'] = "Invalid password.";
            }
        } else {
            // ✅ Handle default admin login (if no DB match)
            if ($email === $defaultAdmin['email'] && $password === $defaultAdmin['password']) {
                $_SESSION['admin'] = [
                    'id' => 0,
                    'email' => $defaultAdmin['email'],
                    'role' => 'admin',
                    'default' => true
                ];
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password.";
            }
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header("Location: ../auth/login.php");
    exit;
}
?>
