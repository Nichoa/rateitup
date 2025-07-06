<?php
// includes/functions.php

// Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Cek apakah user adalah admin
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Redirect kalau belum login
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /rateitup/auth/login.php');
        exit();
    }
}

// Redirect kalau bukan admin
function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header('Location: /rateitup/user/dashboard.php');
        exit();
    }
}

// Fungsi untuk menampilkan rating bintang
function displayStars($rating)
{
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '★';
        } else {
            $output .= '☆';
        }
    }
    return $output;
}

// Fungsi untuk escape output
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Fungsi untuk menampilkan alert
function getAlert()
{
    if (isset($_SESSION['success'])) {
        $msg = $_SESSION['success'];
        unset($_SESSION['success']);
        return "<div class='alert alert-success'>$msg</div>";
    }

    if (isset($_SESSION['error'])) {
        $msg = $_SESSION['error'];
        unset($_SESSION['error']);
        return "<div class='alert alert-danger'>$msg</div>";
    }

    return '';
}
