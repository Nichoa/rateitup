<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check admin access
requireAdmin();

include '../includes/header.php';
?>

<!-- Form Restaurant CSS -->
<link rel="stylesheet" href="/rateitup/assets/styles/form-restaurant.css">

<div class="form-page-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <i class="fas fa-chevron-right"></i>
        <a href="manage_restaurants.php">Manage Restaurants</a>
        <i class="fas fa-chevron-right"></i>
        <span>Tambah Restaurant</span>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-plus-circle"></i> Tambah Restaurant Baru
            </h1>
            <p class="form-subtitle">Tambahkan restaurant baru ke dalam sistem</p>
        </div>

        <!-- Alert Messages -->
        <?= getAlert() ?>

        <!-- Add Form -->
        <form action="process/create_restaurant.php" method="POST" id="addForm">
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-store"></i> Nama Restaurant
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="Contoh: Warung Nasi Padang"
                    required
                    maxlength="100">
                <div class="char-counter" id="nameCounter">
                    <span id="nameCount">0</span>/100 karakter
                </div>
                <div class="error-message">Nama restaurant harus diisi</div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Alamat Lengkap
                </label>
                <textarea id="address"
                    name="address"
                    class="form-textarea"
                    placeholder="Contoh: Jl. Sudirman No. 123, Jakarta"
                    required
                    maxlength="255"></textarea>
                <div class="char-counter" id="addressCounter">
                    <span id="addressCount">0</span>/255 karakter
                </div>
                <div class="input-helper">
                    <i class="fas fa-lightbulb"></i>
                    <span>Tuliskan alamat selengkap mungkin untuk memudahkan pengunjung</span>
                </div>
                <div class="error-message">Alamat restaurant harus diisi</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-plus-circle"></i> Tambah Restaurant
                </button>
                <a href="manage_restaurants.php" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Form Enhancement -->
<script>
    // Character Counter
    document.getElementById('name').addEventListener('input', function() {
        const length = this.value.length;
        const counter = document.getElementById('nameCount');
        const container = document.getElementById('nameCounter');

        counter.textContent = length;

        if (length > 80) {
            container.classList.add('warning');
        } else {
            container.classList.remove('warning');
        }

        if (length >= 100) {
            container.classList.add('danger');
        } else {
            container.classList.remove('danger');
        }
    });

    document.getElementById('address').addEventListener('input', function() {
        const length = this.value.length;
        const counter = document.getElementById('addressCount');
        const container = document.getElementById('addressCounter');

        counter.textContent = length;

        if (length > 200) {
            container.classList.add('warning');
        } else {
            container.classList.remove('warning');
        }

        if (length >= 255) {
            container.classList.add('danger');
        } else {
            container.classList.remove('danger');
        }
    });

    // Form Validation
    document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('name');
        const address = document.getElementById('address');
        let isValid = true;

        // Validate name
        if (name.value.trim() === '') {
            name.closest('.form-group').classList.add('error');
            isValid = false;
        } else {
            name.closest('.form-group').classList.remove('error');
        }

        // Validate address
        if (address.value.trim() === '') {
            address.closest('.form-group').classList.add('error');
            isValid = false;
        } else {
            address.closest('.form-group').classList.remove('error');
        }

        if (isValid) {
            // Add loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;

            // Submit form
            this.submit();
        }
    });

    // Remove error on input
    document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
        input.addEventListener('input', function() {
            this.closest('.form-group').classList.remove('error');
        });
    });
</script>

<?php include '../includes/footer.php'; ?>