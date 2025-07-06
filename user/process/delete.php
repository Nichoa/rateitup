<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek login
requireLogin();

$id = $_GET['id'] ?? 0;

if ($id) {
    // Hapus review (hanya bisa hapus milik sendiri)
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Review berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal hapus review!';
    }
}

header('Location: ../dashboard.php');
exit();
