<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Cek admin
requireAdmin();

// Ambil semua check-ins
$status_filter = $_GET['status'] ?? 'all';

$query = "
    SELECT c.*, u.username, r.name as restaurant_name 
    FROM check_ins c 
    JOIN users u ON c.user_id = u.id 
    JOIN restaurants r ON c.restaurant_id = r.id 
";

if ($status_filter !== 'all') {
    $query .= " WHERE c.status = :status ";
}

$query .= " ORDER BY 
            CASE c.status
                WHEN 'pending' THEN 1
                WHEN 'confirmed' THEN 2
                ELSE 3
            END,
            c.check_in_date DESC, 
            c.check_in_time DESC";

$stmt = $pdo->prepare($query);
if ($status_filter !== 'all') {
    $stmt->execute(['status' => $status_filter]);
} else {
    $stmt->execute();
}
$checkins = $stmt->fetchAll();

include '../includes/header.php';
?>

<link rel="stylesheet" href="/rateitup/assets/styles/manage-checkins.css">
<link rel="stylesheet" href="/rateitup/assets/styles/admin-dashboard.css">
<div class="manage-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-calendar-check"></i> Manage Check-ins
        </h1>
        <p class="page-subtitle">Kelola reservasi dan check-in dari pengguna</p>
    </div>

    <?= getAlert() ?>

    <div class="table-container">
        <div class="controls-section">
            <div class="filter-buttons">
                <a href="?status=all" class="btn btn-sm <?= $status_filter == 'all' ? 'active' : '' ?>">Semua</a>
                <a href="?status=pending" class="btn btn-sm <?= $status_filter == 'pending' ? 'active' : '' ?>">Pending</a>
                <a href="?status=confirmed" class="btn btn-sm <?= $status_filter == 'confirmed' ? 'active' : '' ?>">Confirmed</a>
                <a href="?status=completed" class="btn btn-sm <?= $status_filter == 'completed' ? 'active' : '' ?>">Completed</a>
                <a href="?status=cancelled" class="btn btn-sm <?= $status_filter == 'cancelled' ? 'active' : '' ?>">Cancelled</a>
            </div>
            <a href="dashboard.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>

        <?php if (empty($checkins)): ?>
            <div class="empty-pending">
                <i class="fas fa-check-circle"></i>
                <p>Tidak ada data check-in untuk filter ini.</p>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="checkins-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Restaurant</th>
                            <th>Tanggal & Waktu</th>
                            <th>Jumlah Orang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($checkins as $checkin): ?>
                            <tr>
                                <td><?= e($checkin['username']) ?></td>
                                <td><?= e($checkin['restaurant_name']) ?></td>
                                <td>
                                    <?= date('d M Y', strtotime($checkin['check_in_date'])) ?><br>
                                    <small><?= date('H:i', strtotime($checkin['check_in_time'])) ?> WIB</small>
                                </td>
                                <td><?= $checkin['number_of_people'] ?> orang</td>
                                <td>
                                    <?php if ($checkin['status'] == 'pending'): ?>
                                        <span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>
                                    <?php elseif ($checkin['status'] == 'confirmed'): ?>
                                        <span class="badge badge-approved"><i class="fas fa-check"></i> Confirmed</span>
                                    <?php elseif ($checkin['status'] == 'cancelled'): ?>
                                        <span class="badge badge-rejected"><i class="fas fa-times"></i> Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #17a2b8; color: white;"><i class="fas fa-check-double"></i> Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($checkin['status'] == 'pending'): ?>
                                            <a href="process/confirm_checkin.php?id=<?= $checkin['id'] ?>"
                                                class="btn btn-success btn-sm">Confirm</a>
                                            <a href="process/cancel_checkin_admin.php?id=<?= $checkin['id'] ?>"
                                                class="btn btn-danger btn-sm">Cancel</a>
                                        <?php elseif ($checkin['status'] == 'confirmed' && strtotime($checkin['check_in_date'] . ' ' . $checkin['check_in_time']) < time()): ?>
                                            <a href="process/complete_checkin.php?id=<?= $checkin['id'] ?>"
                                                class="btn btn-primary btn-sm">Complete</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>