<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Username dan password harus diisi!';
        header('Location: login.php');
        exit();
    }

    try {
        // Query user dengan prepared statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && $user['password'] === $password) {
            // Regenerate session ID untuk security
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time();

            // Log activity (optional)
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../user/dashboard.php');
            }
            exit();
        } else {
            // Login failed
            $_SESSION['error'] = 'Username atau password salah!';
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        // Database error
        $_SESSION['error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        header('Location: login.php');
        exit();
    }
} else {
    // Direct access not allowed
    header('Location: login.php');
    exit();
}
