<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek admin
requireAdmin();

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("UPDATE reviews SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = 'Review berhasil di-reject!';
}

header('Location: ../dashboard.php');
exit();
