<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check login
requireLogin();

// Fetch restaurants for dropdown
$stmt = $pdo->query("SELECT id, name FROM restaurants ORDER BY name ASC");
$restaurants = $stmt->fetchAll();

include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/add-review.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="form-container">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Review Baru</h1>
    </div>

    <?= getAlert() ?>

    <form action="process/create.php" method="post">
        <div class="form-group">
            <label for="restaurant_id">Pilih Restaurant</label>
            <select name="restaurant_id" id="restaurant_id" class="form-select" required>
                <option value="" disabled selected>-- Nama Restaurant --</option>
                <?php foreach ($restaurants as $restaurant): ?>
                    <option value="<?= $restaurant['id'] ?>"><?= e($restaurant['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Rating Anda</label>
            <div class="star-rating">
                <input type="radio" id="5-stars" name="rating" value="5" required /><label for="5-stars" class="star">&#9733;</label>
                <input type="radio" id="4-stars" name="rating" value="4" /><label for="4-stars" class="star">&#9733;</label>
                <input type="radio" id="3-stars" name="rating" value="3" /><label for="3-stars" class="star">&#9733;</label>
                <input type="radio" id="2-stars" name="rating" value="2" /><label for="2-stars" class="star">&#9733;</label>
                <input type="radio" id="1-star" name="rating" value="1" /><label for="1-star" class="star">&#9733;</label>
            </div>
        </div>

        <div class="form-group">
            <label for="comment">Tulis Review Anda</label>
            <textarea name="comment" id="comment" rows="6" class="form-control" placeholder="Bagikan pengalaman kuliner Anda di sini..." required></textarea>
        </div>

        <div class="form-actions">
            <a href="dashboard.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-paper-plane"></i> Kirim Review
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>