<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check admin access
requireAdmin();

// Get restaurant ID
$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = 'Restaurant ID tidak valid!';
    header('Location: manage_restaurants.php');
    exit();
}

// Get restaurant data
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$id]);
$restaurant = $stmt->fetch();

if (!$restaurant) {
    $_SESSION['error'] = 'Restaurant tidak ditemukan!';
    header('Location: manage_restaurants.php');
    exit();
}

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
        <span>Edit Restaurant</span>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i> Edit Restaurant
            </h1>
            <p class="form-subtitle">Update informasi restaurant</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Perubahan pada restaurant akan mempengaruhi semua review yang terkait.</p>
        </div>

        <!-- Alert Messages -->
        <?= getAlert() ?>

        <!-- Edit Form -->
        <form action="process/update_restaurant.php" method="POST" id="editForm">
            <input type="hidden" name="id" value="<?= $restaurant['id'] ?>">

            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-store"></i> Nama Restaurant
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="<?= htmlspecialchars($restaurant['name']) ?>"
                    required
                    maxlength="100">
                <div class="char-counter" id="nameCounter">
                    <span id="nameCount"><?= strlen($restaurant['name']) ?></span>/100 karakter
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
                    required
                    maxlength="255"><?= htmlspecialchars($restaurant['address']) ?></textarea>
                <div class="char-counter" id="addressCounter">
                    <span id="addressCount"><?= strlen($restaurant['address']) ?></span>/255 karakter
                </div>
                <div class="input-helper">
                    <i class="fas fa-lightbulb"></i>
                    <span>Tuliskan alamat selengkap mungkin untuk memudahkan pengunjung</span>
                </div>
                <div class="error-message">Alamat restaurant harus diisi</div>
            </div>

            <!-- Preview Box -->
            <div class="preview-box" id="previewBox">
                <div class="preview-title">Preview:</div>
                <div id="previewContent"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-update" id="submitBtn">
                    <i class="fas fa-save"></i> Update Restaurant
                </button>
                <a href="manage_restaurants.php" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>

        <!-- Success Message (Hidden) -->
        <div class="form-success" id="successMessage">
            <i class="fas fa-check-circle success-icon"></i>
            <h3>Restaurant Berhasil Diupdate!</h3>
            <p>Redirecting...</p>
        </div>
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
    document.getElementById('editForm').addEventListener('submit', function(e) {
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