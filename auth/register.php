<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
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
    <title>Register - Rate It Up</title>

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
            <h1 class="auth-title">Daftar Akun Baru</h1>
            <p class="auth-subtitle">Bergabung dan mulai review tempat kuliner favorit!</p>
        </div>

        <!-- Alert Messages -->
        <?= getAlert() ?>

        <!-- Registration Form -->
        <form action="process_register.php" method="POST" class="auth-form" id="registerForm">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text"
                    id="username"
                    name="username"
                    class="form-input"
                    placeholder="Pilih username unik"
                    autocomplete="username"
                    required>
                <span class="input-hint">Username akan digunakan untuk login</span>
                <span class="form-error">Username sudah digunakan</span>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Minimal 6 karakter"
                    autocomplete="new-password"
                    required>
                <span class="input-hint">Password minimal 6 karakter</span>
                <span class="form-error">Password terlalu pendek</span>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">
                    <i class="fas fa-check-circle"></i> Konfirmasi Password
                </label>
                <input type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="form-input"
                    placeholder="Ulangi password"
                    autocomplete="new-password"
                    required>
                <span class="form-error">Password tidak sama</span>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                    id="terms"
                    class="checkbox-input"
                    required>
                <label for="terms" class="checkbox-label">
                    Saya setuju dengan syarat dan ketentuan
                </label>
            </div>

            <button type="submit" class="auth-submit">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>

        <!-- Links -->
        <div class="auth-links">
            <p class="auth-link-text">
                Sudah punya akun?
                <a href="login.php" class="auth-link">Login disini</a>
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="/rateitup/assets/js/script.js"></script>
    <script>
        // Form Validation
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        // Real-time password match validation
        confirmPassword.addEventListener('input', function() {
            const parent = this.closest('.form-group');
            if (this.value === password.value && this.value !== '') {
                parent.classList.remove('error');
                parent.classList.add('success');
            } else if (this.value !== '') {
                parent.classList.add('error');
                parent.classList.remove('success');
            }
        });

        // Password length validation
        password.addEventListener('input', function() {
            const parent = this.closest('.form-group');
            if (this.value.length >= 6) {
                parent.classList.remove('error');
            } else if (this.value !== '') {
                parent.classList.add('error');
            }
        });

        // Form submit
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.auth-submit');

            // Validate password match
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.closest('.form-group').classList.add('error');
                return;
            }

            // Add loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>