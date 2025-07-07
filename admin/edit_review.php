<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Wajib admin
requireAdmin();

// Ambil ID review dari URL
$id = $_GET['id'] ?? 0;

// Ambil data review dari database
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ?");
$stmt->execute([$id]);
$review = $stmt->fetch();

// Jika review tidak ditemukan, redirect
if (!$review) {
    $_SESSION['error'] = 'Review tidak ditemukan!';
    header('Location: dashboard.php');
    exit();
}

include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/form-restaurant.css">

<div class="form-page-container">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">
                <i class="fas fa-edit"></i> Edit Review
            </h1>
            <p class="form-subtitle">Moderasi rating, komentar, dan status review.</p>
        </div>

        <?= getAlert() ?>

        <form action="process/update_review.php" method="POST">
            <input type="hidden" name="id" value="<?= e($review['id']) ?>">

            <div class="form-group">
                <label for="rating" class="form-label"><i class="fas fa-star"></i> Rating</label>
                <select name="rating" id="rating" class="form-input" required>
                    <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>★★★★★ (5 - Excellent)</option>
                    <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>★★★★☆ (4 - Bagus)</option>
                    <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>★★★☆☆ (3 - Cukup)</option>
                    <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 - Kurang)</option>
                    <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 - Buruk)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="comment" class="form-label"><i class="fas fa-comment"></i> Komentar</label>
                <textarea id="comment" name="comment" class="form-textarea" required><?= e($review['comment']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="status" class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                <select name="status" id="status" class="form-input" required>
                    <option value="approved" <?= $review['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="pending" <?= $review['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="rejected" <?= $review['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-update">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="dashboard.php" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>