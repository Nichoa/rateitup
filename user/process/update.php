<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek login
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $restaurant_id = $_POST['restaurant_id'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $comment = $_POST['comment'] ?? '';

    // Validasi
    if (empty($restaurant_id) || empty($rating) || empty($comment)) {
        $_SESSION['error'] = 'Semua field harus diisi!';
        header('Location: ../edit_review.php?id=' . $id);
        exit();
    }

    // Update review (hanya bisa update milik sendiri)
    try {
        $stmt = $pdo->prepare("
            UPDATE reviews 
            SET restaurant_id = ?, rating = ?, comment = ?, status = 'pending'
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$restaurant_id, $rating, $comment, $id, $_SESSION['user_id']]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'Review berhasil diupdate! Menunggu approval ulang.';
        } else {
            $_SESSION['error'] = 'Gagal update review!';
        }

        header('Location: ../dashboard.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal update review!';
        header('Location: ../edit_review.php?id=' . $id);
        exit();
    }
} else {
    header('Location: ../dashboard.php');
    exit();
}
