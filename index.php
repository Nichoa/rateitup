<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Kalau sudah login
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: user/dashboard.php');
    }
} else {
    // Kalau belum login - redirect ke halaman publik
    header('Location: public_reviews.php');
}
exit();
