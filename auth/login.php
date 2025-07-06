<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rate It Up</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="/rateitup/assets/styles/styles.css">
    <link rel="stylesheet" href="/rateitup/assets/styles/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="auth-body">
    <div class="auth-container">
        <!-- Logo Section -->
        <div class="auth-logo">
            <i class="fas fa-utensils auth-logo-icon"></i>
            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Login untuk melanjutkan review kuliner</p>
        </div>

        <!-- Alert Messages -->
        <?= getAlert() ?>

        <!-- Login Form -->
        <form action="process_login.php" method="POST" class="auth-form" id="loginForm">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text"
                    id="username"
                    name="username"
                    class="form-input"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required>
            </div>

            <button type="submit" class="auth-submit">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <!-- Links -->
        <div class="auth-links">
            <p class="auth-link-text">
                Belum punya akun?
                <a href="register.php" class="auth-link">Daftar disini</a>
            </p>
        </div>

        <!-- Demo Info -->
        <div class="demo-info">
            <p class="demo-info-title">
                <i class="fas fa-info-circle"></i> Demo Account
            </p>
            <p class="demo-info-text"><br>
                User: user1 / user123
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="/rateitup/assets/js/script.js"></script>
    <script>
        // Form submit with loading state
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.auth-submit');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>