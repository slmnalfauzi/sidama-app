<?php
require_once 'db.php';
require_once 'includes/functions.php';
require_once 'auth.php';

requireAdmin();

$id = $_GET['id'] ?? 0;
$errors = [];

// Get data mahasiswa
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$mahasiswa = $stmt->fetch();

if (!$mahasiswa) {
    $_SESSION['error'] = 'Data tidak ditemukan';
    redirect('mahasiswa.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $foto = $mahasiswa['foto'];
    
    // Validasi
    if (empty($nim)) $errors[] = 'NIM harus diisi';
    elseif (!validasiNIM($nim)) $errors[] = 'Format NIM tidak valid';
    
    if (empty($nama)) $errors[] = 'Nama harus diisi';
    if (empty($email)) $errors[] = 'Email harus diisi';
    elseif (!validasiEmail($email)) $errors[] = 'Format email tidak valid';
    
    if (empty($tanggal_lahir)) $errors[] = 'Tanggal lahir harus diisi';
    if (empty($jenis_kelamin)) $errors[] = 'Jenis kelamin harus dipilih';
    
    // Cek NIM duplikat (kecuali NIM sendiri)
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = ? AND id != ?");
        $stmt->execute([$nim, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'NIM sudah terdaftar';
        }
    }
    
    // Upload foto baru
    if (empty($errors) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFoto($_FILES['foto']);
        if ($upload['success']) {
            hapusFoto($mahasiswa['foto']);
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }
    
    // Update data
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = ?, nama = ?, email = ?, tanggal_lahir = ?, jenis_kelamin = ?, alamat = ?, foto = ? WHERE id = ?");
        if ($stmt->execute([$nim, $nama, $email, $tanggal_lahir, $jenis_kelamin, $alamat, $foto, $id])) {
            $_SESSION['success'] = 'Data mahasiswa berhasil diupdate';
            redirect('mahasiswa.php');
        } else {
            $errors[] = 'Gagal mengupdate data';
        }
    }
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Mahasiswa</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" value="<?= clean($mahasiswa['nim']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= clean($mahasiswa['nama']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?= clean($mahasiswa['email']) ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $mahasiswa['tanggal_lahir'] ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="">Pilih...</option>
                                <option value="L" <?= $mahasiswa['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $mahasiswa['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3"><?= clean($mahasiswa['alamat']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <div class="mb-2">
                            <img src="uploads/<?= clean($mahasiswa['foto']) ?>" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                        <input type="file" class="form-control" name="foto" accept="image/jpeg,image/jpg,image/png">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>Update
                        </button>
                        <a href="mahasiswa.php" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
