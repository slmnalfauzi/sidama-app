<?php
session_start();

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Middleware untuk halaman yang memerlukan login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

// Middleware untuk halaman yang memerlukan role admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini';
        redirect('index.php');
    }
}

// Fungsi logout
function logout() {
    session_destroy();
    redirect('login.php');
}
?>
