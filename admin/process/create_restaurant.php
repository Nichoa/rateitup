<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validasi
    if (empty($name) || empty($address)) {
        $_SESSION['error'] = 'Nama dan alamat harus diisi!';
        header('Location: ../add_restaurant.php');
        exit();
    }

    // Insert restaurant
    try {
        $stmt = $pdo->prepare("INSERT INTO restaurants (name, address) VALUES (?, ?)");
        $stmt->execute([$name, $address]);

        $_SESSION['success'] = 'Restaurant "' . $name . '" berhasil ditambahkan!';
        header('Location: ../manage_restaurants.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal menambahkan restaurant!';
        header('Location: ../add_restaurant.php');
        exit();
    }
} else {
    header('Location: ../manage_restaurants.php');
    exit();
}
