<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek admin
requireAdmin();

$id = $_GET['id'] ?? 0;

if ($id) {
    // Admin bisa hapus review siapa saja
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'Review berhasil dihapus!';
    }
}

header('Location: ../dashboard.php');
exit();
