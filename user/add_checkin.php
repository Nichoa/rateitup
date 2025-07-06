<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Cek login
requireLogin();

// Ambil list restaurants
$stmt = $pdo->query("SELECT * FROM restaurants ORDER BY name");
$restaurants = $stmt->fetchAll();

include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/add-checkin.css">

<div class="form-container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Check-in / Reservasi</h1>
    </div>

    <?= getAlert() ?>

    <form action="process/create_checkin.php" method="POST">
        <div class="form-group">
            <label for="restaurant_id">Pilih Restaurant</label>
            <select name="restaurant_id" id="restaurant_id" class="form-select" required>
                <option value="">-- Pilih Restaurant --</option>
                <?php foreach ($restaurants as $restaurant): ?>
                    <option value="<?= $restaurant['id'] ?>">
                        <?= e($restaurant['name']) ?> - <?= e($restaurant['address']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="check_in_date">Tanggal Check-in</label>
            <input type="date" name="check_in_date" id="check_in_date" class="form-control"
                min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label for="check_in_time">Waktu Check-in</label>
            <input type="time" name="check_in_time" id="check_in_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="number_of_people">Jumlah Orang</label>
            <select name="number_of_people" id="number_of_people" class="form-select" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> orang</option>
                <?php endfor; ?>
                <option value="11">Lebih dari 10 orang</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Catatan (Opsional)</label>
            <textarea name="notes" id="notes" rows="3" class="form-control"
                placeholder="Contoh: Meja dekat jendela, ada anak kecil, dll"></textarea>
        </div>

        <div class="form-actions">
            <a href="my_checkins.php" class="btn btn-cancel"><i class="fas fa-times"></i> Batal</a>
            <button type="submit" class="btn btn-submit"><i class="fas fa-check"></i> Submit Check-in</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>