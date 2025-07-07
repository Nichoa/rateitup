<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Wajib admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'] ?? 0;
    $rating = $_POST['rating'] ?? '';
    $comment = trim($_POST['comment'] ?? '');
    $status = $_POST['status'] ?? '';

    // Validasi sederhana
    if (empty($id) || empty($rating) || empty($comment) || empty($status)) {
        $_SESSION['error'] = 'Semua data harus diisi!';
        header('Location: ../edit_review.php?id=' . $id);
        exit();
    }

    try {
        // Update data review di database
        $stmt = $pdo->prepare("UPDATE reviews SET rating = ?, comment = ?, status = ? WHERE id = ?");
        $stmt->execute([$rating, $comment, $status, $id]);

        $_SESSION['success'] = 'Review berhasil diupdate!';
        header('Location: ../dashboard.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal mengupdate review!';
        header('Location: ../edit_review.php?id=' . $id);
        exit();
    }
} else {
    // Redirect jika diakses langsung
    header('Location: ../dashboard.php');
    exit();
}
