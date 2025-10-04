<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'includes/functions.php';

requireLogin();

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$mhs = $stmt->fetch();

if ($mhs) {
    $mhs['umur'] = hitungUmur($mhs['tanggal_lahir']);
    $mhs['tanggal_lahir_formatted'] = formatTanggal($mhs['tanggal_lahir']);
    echo json_encode(['success' => true, 'data' => $mhs]);
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
}
?>
