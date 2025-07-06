<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($id) || empty($name) || empty($address)) {
        $_SESSION['error'] = 'Semua data harus diisi!';
        header('Location: ../edit_restaurant.php?id=' . $id);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE restaurants SET name = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $address, $id]);

        $_SESSION['success'] = 'Data restaurant berhasil diupdate!';
        header('Location: ../manage_restaurants.php');
        exit();
    } catch (PDOException $e) {
        error_log("Update Restaurant Error: " . $e->getMessage());
        $_SESSION['error'] = 'Gagal mengupdate data restaurant!';
        header('Location: ../edit_restaurant.php?id=' . $id);
        exit();
    }
} else {
    header('Location: ../manage_restaurants.php');
    exit();
}
