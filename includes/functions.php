<?php
// Fungsi menghitung umur dari tanggal lahir
function hitungUmur($tanggal_lahir) {
    $lahir = new DateTime($tanggal_lahir);
    $sekarang = new DateTime();
    $umur = $sekarang->diff($lahir);
    return $umur->y;
}

// Fungsi format tanggal Indonesia
function formatTanggal($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Fungsi upload foto
function uploadFoto($file) {
    $target_dir = "uploads/";
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Validasi tipe file
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan. Hanya JPG dan PNG.'];
    }
    
    // Validasi ukuran file
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 2MB.'];
    }
    
    // Generate nama file unik
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $target_file = $target_dir . $filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $filename];
    } else {
        return ['success' => false, 'message' => 'Gagal mengupload file.'];
    }
}

// Fungsi hapus foto
function hapusFoto($filename) {
    if ($filename !== 'default.jpg' && file_exists("uploads/" . $filename)) {
        unlink("uploads/" . $filename);
    }
}

// Fungsi validasi NIM
function validasiNIM($nim) {
    return preg_match('/^[0-9]{7,20}$/', $nim);
}

// Fungsi validasi email
function validasiEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi pagination
function getPagination($total, $per_page, $current_page) {
    $total_pages = ceil($total / $per_page);
    return [
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'prev_page' => $current_page - 1,
        'next_page' => $current_page + 1
    ];
}
?>
