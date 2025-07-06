<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Cek login
requireLogin();

$id = $_GET['id'] ?? 0;

// Ambil review berdasarkan ID dan user yang login
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$review = $stmt->fetch();

if (!$review) {
    $_SESSION['error'] = 'Review tidak ditemukan!';
    header('Location: dashboard.php');
    exit();
}

// Ambil list restaurants
$stmt = $pdo->query("SELECT * FROM restaurants ORDER BY name");
$restaurants = $stmt->fetchAll();

include '../includes/header.php';
?>

<h1>Edit Review</h1>

<div class="card">
    <form action="process/update.php" method="POST">
        <input type="hidden" name="id" value="<?= $review['id'] ?>">

        <div class="form-group">
            <label for="restaurant_id">Restaurant</label>
            <select name="restaurant_id" id="restaurant_id" required>
                <?php foreach ($restaurants as $restaurant): ?>
                    <option value="<?= $restaurant['id'] ?>" <?= $restaurant['id'] == $review['restaurant_id'] ? 'selected' : '' ?>>
                        <?= e($restaurant['name']) ?> - <?= e($restaurant['address']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <select name="rating" id="rating" required>
                <option value="5" <?= $review['rating'] == 5 ? 'selected' : '' ?>>★★★★★ (5 - Excellent)</option>
                <option value="4" <?= $review['rating'] == 4 ? 'selected' : '' ?>>★★★★☆ (4 - Bagus)</option>
                <option value="3" <?= $review['rating'] == 3 ? 'selected' : '' ?>>★★★☆☆ (3 - Cukup)</option>
                <option value="2" <?= $review['rating'] == 2 ? 'selected' : '' ?>>★★☆☆☆ (2 - Kurang)</option>
                <option value="1" <?= $review['rating'] == 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 - Buruk)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="comment">Review Anda</label>
            <textarea name="comment" id="comment" rows="5" required><?= e($review['comment']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-warning">Update Review</button>
        <a href="dashboard.php" class="btn">Batal</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>