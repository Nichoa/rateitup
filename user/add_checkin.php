<?php
// FILE: /user/add_checkin.php
// FUNGSI: Halaman untuk membuat check-in/reservasi baru.

require_once '../config/database.php';
require_once '../includes/functions.php';

// Wajibkan login untuk akses
requireLogin();

// Ambil ID user dari session
$current_user_id = $_SESSION['user_id'];

// Ambil daftar restoran yang pernah di-review oleh user ini
$stmt = $pdo->prepare("
    SELECT DISTINCT r.id, r.name, r.address
    FROM restaurants r
    JOIN reviews rv ON r.id = rv.restaurant_id
    WHERE rv.user_id = :user_id AND rv.status = 'approved'
    ORDER BY r.name ASC
");
$stmt->execute(['user_id' => $current_user_id]);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tampilkan header
include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/add-checkin.css">

<div class="form-container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Check-in / Reservasi</h1>
    </div>

    <?= getAlert() // Tampilkan notifikasi 
    ?>

    <?php if (empty($restaurants)): ?>
        <div class="alert" style="background-color: #fefcbf; color: #92400e; border: 1px solid #fde68a; text-align: center;">
            <strong>Anda belum bisa check-in.</strong><br>
            Silakan buat review terlebih dahulu untuk restoran yang ingin Anda kunjungi.
            <a href="add_review.php" style="color: #92400e; font-weight: bold;">Buat review sekarang</a>.
        </div>
    <?php else: ?>
        <form action="process/create_checkin.php" method="POST">

            <div class="form-group">
                <label for="restaurant_id"><i class="fas fa-store"></i> Pilih Restoran</label>
                <select name="restaurant_id" id="restaurant_id" class="form-select" required>
                    <option value="" disabled selected>-- Hanya restoran yang pernah Anda review --</option>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <option value="<?= $restaurant['id'] ?>">
                            <?= e($restaurant['name']) ?> (<?= e($restaurant['address']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="check_in_date"><i class="fas fa-calendar-alt"></i> Tanggal</label>
                <input type="date" name="check_in_date" id="check_in_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="check_in_time"><i class="fas fa-clock"></i> Waktu</label>
                <input type="time" name="check_in_time" id="check_in_time" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="number_of_people"><i class="fas fa-users"></i> Jumlah Orang</label>
                <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="notes"><i class="fas fa-pencil-alt"></i> Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Contoh: Request meja di dekat jendela"></textarea>
            </div>

            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-cancel"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-submit"><i class="fas fa-check"></i> Submit Check-in</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php
// Tampilkan footer
include '../includes/footer.php';
?>