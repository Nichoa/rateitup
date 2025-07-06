<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Cek login
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_id = $_POST['restaurant_id'] ?? '';
    $check_in_date = $_POST['check_in_date'] ?? '';
    $check_in_time = $_POST['check_in_time'] ?? '';
    $number_of_people = $_POST['number_of_people'] ?? 1;
    $notes = $_POST['notes'] ?? '';

    // Validasi
    if (empty($restaurant_id) || empty($check_in_date) || empty($check_in_time)) {
        $_SESSION['error'] = 'Restaurant, tanggal, dan waktu harus diisi!';
        header('Location: ../add_checkin.php');
        exit();
    }

    // Validasi tanggal tidak boleh di masa lalu
    if (strtotime($check_in_date) < strtotime(date('Y-m-d'))) {
        $_SESSION['error'] = 'Tanggal check-in tidak boleh di masa lalu!';
        header('Location: ../add_checkin.php');
        exit();
    }

    // Insert check-in
    try {
        $stmt = $pdo->prepare("
            INSERT INTO check_ins (user_id, restaurant_id, check_in_date, check_in_time, number_of_people, notes) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $restaurant_id,
            $check_in_date,
            $check_in_time,
            $number_of_people,
            $notes
        ]);

        $_SESSION['success'] = 'Check-in berhasil! Menunggu konfirmasi dari restaurant.';
        header('Location: ../my_checkins.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Gagal membuat check-in!';
        header('Location: ../add_checkin.php');
        exit();
    }
} else {
    header('Location: ../dashboard.php');
    exit();
}
