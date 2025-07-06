<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek admin
requireAdmin();

$id = $_GET['id'] ?? 0;

if ($id) {
    // Update status jadi completed
    $stmt = $pdo->prepare("UPDATE check_ins SET status = 'completed' WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Check-in telah selesai!';
    } else {
        $_SESSION['error'] = 'Gagal mengupdate status check-in!';
    }
}

header('Location: ../manage_checkins.php');
exit();
