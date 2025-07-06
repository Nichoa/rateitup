<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation array
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = 'Username harus diisi!';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username minimal 3 karakter!';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username hanya boleh huruf, angka, dan underscore!';
    }

    // Password validation
    if (empty($password)) {
        $errors[] = 'Password harus diisi!';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter!';
    }

    // Confirm password
    if ($password !== $confirm_password) {
        $errors[] = 'Password tidak sama!';
    }

    // If validation fails
    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: register.php');
        exit();
    }

    try {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Username sudah digunakan! Pilih username lain.';
            header('Location: register.php');
            exit();
        }

        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, role, created_at) 
            VALUES (?, ?, 'user', NOW())
        ");
        $stmt->execute([$username, $password]);

        // Get the new user ID
        $user_id = $pdo->lastInsertId();

        // Log activity
        error_log("New user registered: $username (ID: $user_id)");

        // Success message
        $_SESSION['success'] = 'Registrasi berhasil! Silakan login dengan akun yang telah dibuat.';
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        // Database error
        if ($e->getCode() == 23000) {
            // Duplicate entry
            $_SESSION['error'] = 'Username sudah digunakan!';
        } else {
            $_SESSION['error'] = 'Registrasi gagal! Silakan coba lagi.';
            error_log("Registration error: " . $e->getMessage());
        }
        header('Location: register.php');
        exit();
    }
} else {
    // Direct access not allowed
    header('Location: register.php');
    exit();
}
