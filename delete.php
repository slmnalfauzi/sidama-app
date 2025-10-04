<?php
require_once 'db.php';
require_once 'includes/functions.php';
require_once 'auth.php';

requireAdmin();

$id = $_GET['id'] ?? 0;

// Get data mahasiswa
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$mahasiswa = $stmt->fetch();

if ($mahasiswa) {
    // Hapus foto
    hapusFoto($mahasiswa['foto']);
    
    // Hapus data
    $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = 'Data mahasiswa berhasil dihapus';
    } else {
        $_SESSION['error'] = 'Gagal menghapus data';
    }
} else {
    $_SESSION['error'] = 'Data tidak ditemukan';
}

redirect('mahasiswa.php');
?>
