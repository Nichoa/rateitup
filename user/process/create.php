<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek login
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_id = $_POST['restaurant_id'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $comment = $_POST['comment'] ?? '';

    // Validasi
    if (empty($restaurant_id) || empty($rating) || empty($comment)) {
        $_SESSION['error'] = 'Semua field harus diisi!';
        header('Location: ../add_review.php');
        exit();
    }

    // Insert review
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reviews (user_id, restaurant_id, rating, comment, status) 
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$_SESSION['user_id'], $restaurant_id, $rating, $comment]);

        $_SESSION['success'] = 'Review berhasil ditambahkan! Menunggu approval admin.';
        header('Location: ../dashboard.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal menambahkan review!';
        header('Location: ../add_review.php');
        exit();
    }
} else {
    header('Location: ../dashboard.php');
    exit();
}
