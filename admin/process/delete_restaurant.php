<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Wajib admin
requireAdmin();

$id = $_GET['id'] ?? 0;

if (empty($id)) {
    $_SESSION['error'] = 'ID Restaurant tidak valid!';
    header('Location: ../manage_restaurants.php');
    exit();
}

try {
    // Mulai transaksi untuk memastikan semua data terkait terhapus
    $pdo->beginTransaction();

    // 1. Hapus semua check-in yang terkait dengan restoran ini
    $stmtCheckins = $pdo->prepare("DELETE FROM check_ins WHERE restaurant_id = ?");
    $stmtCheckins->execute([$id]);

    // 2. Hapus semua review yang terkait dengan restoran ini
    $stmtReviews = $pdo->prepare("DELETE FROM reviews WHERE restaurant_id = ?");
    $stmtReviews->execute([$id]);

    // 3. Hapus restoran itu sendiri
    $stmtRestaurant = $pdo->prepare("DELETE FROM restaurants WHERE id = ?");
    $stmtRestaurant->execute([$id]);

    // Jika semua berhasil, commit transaksi
    $pdo->commit();

    $_SESSION['success'] = 'Restaurant beserta semua review dan check-in terkait berhasil dihapus permanen.';
    header('Location: ../manage_restaurants.php');
    exit();
} catch (PDOException $e) {
    // Jika ada error, batalkan semua perubahan
    $pdo->rollBack();
    $_SESSION['error'] = 'Gagal menghapus restaurant! Error: ' . $e->getMessage();
    header('Location: ../manage_restaurants.php');
    exit();
}
