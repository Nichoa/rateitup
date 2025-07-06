<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Cek login
requireLogin();

// Ambil check-ins user yang login
$stmt = $pdo->prepare("
    SELECT c.*, r.name as restaurant_name, r.address 
    FROM check_ins c 
    JOIN restaurants r ON c.restaurant_id = r.id 
    WHERE c.user_id = ? 
    ORDER BY c.check_in_date DESC, c.check_in_time DESC
");
$stmt->execute([$_SESSION['user_id']]);
$checkins = $stmt->fetchAll();

include '../includes/header.php';
?>

<h1>My Check-ins / Reservasi</h1>

<?= getAlert() ?>

<div style="margin-bottom: 20px;">
    <a href="add_checkin.php" class="btn btn-success">+ Check-in Baru</a>
    <a href="dashboard.php" class="btn">← Kembali ke Dashboard</a>
</div>

<?php if (empty($checkins)): ?>
    <div class="card">
        <p>Anda belum memiliki check-in. <a href="add_checkin.php">Buat check-in pertama Anda!</a></p>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Restaurant</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Jumlah Orang</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checkins as $checkin): ?>
                <tr>
                    <td>
                        <strong><?= e($checkin['restaurant_name']) ?></strong><br>
                        <small><?= e($checkin['address']) ?></small>
                    </td>
                    <td><?= date('d/m/Y', strtotime($checkin['check_in_date'])) ?></td>
                    <td><?= date('H:i', strtotime($checkin['check_in_time'])) ?></td>
                    <td><?= $checkin['number_of_people'] ?> orang</td>
                    <td>
                        <?php if ($checkin['status'] == 'pending'): ?>
                            <span class="badge badge-pending">Pending</span>
                        <?php elseif ($checkin['status'] == 'confirmed'): ?>
                            <span class="badge badge-approved">Confirmed</span>
                        <?php elseif ($checkin['status'] == 'cancelled'): ?>
                            <span class="badge badge-rejected">Cancelled</span>
                        <?php else: ?>
                            <span class="badge badge-success">Completed</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($checkin['notes'] ?: '-') ?></td>
                    <td>
                        <?php if ($checkin['status'] == 'pending' || $checkin['status'] == 'confirmed'): ?>
                            <?php if (strtotime($checkin['check_in_date']) >= strtotime(date('Y-m-d'))): ?>
                                <a href="process/cancel_checkin.php?id=<?= $checkin['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin batalkan check-in ini?')">Batalkan</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>