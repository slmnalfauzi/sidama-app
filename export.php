<?php
require_once 'db.php';
require_once 'auth.php';

requireLogin();

// Get all data
$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY nama ASC");
$mahasiswa = $stmt->fetchAll();

// Set headers untuk download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_mahasiswa_' . date('Y-m-d') . '.csv');

// Output CSV
$output = fopen('php://output', 'w');

// Header CSV
fputcsv($output, ['NIM', 'Nama', 'Email', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat']);

// Data
foreach ($mahasiswa as $mhs) {
    fputcsv($output, [
        $mhs['nim'],
        $mhs['nama'],
        $mhs['email'],
        $mhs['tanggal_lahir'],
        $mhs['jenis_kelamin'],
        $mhs['alamat']
    ]);
}

fclose($output);
exit();
?>
