<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek admin
requireAdmin();

$id = $_GET['id'] ?? 0;

if ($id) {
    // Update status jadi confirmed
    $stmt = $pdo->prepare("UPDATE check_ins SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Check-in berhasil dikonfirmasi!';
    } else {
        $_SESSION['error'] = 'Gagal mengkonfirmasi check-in!';
    }
}

header('Location: ../manage_checkins.php');
exit();
