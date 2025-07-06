<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek login
requireLogin();

$id = $_GET['id'] ?? 0;

if ($id) {
    // Update status jadi cancelled (hanya bisa cancel milik sendiri)
    $stmt = $pdo->prepare("
        UPDATE check_ins 
        SET status = 'cancelled' 
        WHERE id = ? AND user_id = ? AND check_in_date >= CURDATE()
    ");
    $stmt->execute([$id, $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Check-in berhasil dibatalkan!';
    } else {
        $_SESSION['error'] = 'Gagal membatalkan check-in!';
    }
}

header('Location: ../my_checkins.php');
exit();
