<?php
require_once 'db.php';
require_once 'includes/functions.php';
require_once 'auth.php';

requireAdmin();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $foto = 'default.jpg';
    
    // Validasi
    if (empty($nim)) $errors[] = 'NIM harus diisi';
    elseif (!validasiNIM($nim)) $errors[] = 'Format NIM tidak valid';
    
    if (empty($nama)) $errors[] = 'Nama harus diisi';
    if (empty($email)) $errors[] = 'Email harus diisi';
    elseif (!validasiEmail($email)) $errors[] = 'Format email tidak valid';
    
    if (empty($tanggal_lahir)) $errors[] = 'Tanggal lahir harus diisi';
    if (empty($jenis_kelamin)) $errors[] = 'Jenis kelamin harus dipilih';
    
    // Cek NIM duplikat
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'NIM sudah terdaftar';
        }
    }
    
    // Upload foto
    if (empty($errors) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFoto($_FILES['foto']);
        if ($upload['success']) {
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }
    
    // Insert data
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, email, tanggal_lahir, jenis_kelamin, alamat, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nim, $nama, $email, $tanggal_lahir, $jenis_kelamin, $alamat, $foto])) {
            $_SESSION['success'] = 'Data mahasiswa berhasil ditambahkan';
            redirect('mahasiswa.php');
        } else {
            $errors[] = 'Gagal menyimpan data';
        }
    }
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Tambah Data Mahasiswa</h5>
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
                
                <form method="POST" action="" enctype="multipart/form-data" id="formTambah">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nim" value="<?= $_POST['nim'] ?? '' ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="<?= $_POST['nama'] ?? '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $_POST['tanggal_lahir'] ?? '' ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="">Pilih...</option>
                                <option value="L" <?= ($_POST['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($_POST['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3"><?= $_POST['alamat'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto" accept="image/jpeg,image/jpg,image/png" id="fotoInput">
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        <div id="previewFoto" class="mt-2"></div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-info text-white" onclick="previewData()">
                            <i class="fas fa-eye me-1"></i>Preview
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan
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

 <!-- Modal Preview  -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan Data</button>
            </div>
        </div>
    </div>
</div>

<script>
// Preview foto
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        };
        reader.readAsDataURL(file);
    }
});

function previewData() {
    const form = document.getElementById('formTambah');
    const formData = new FormData(form);
    
    let html = '<table class="table">';
    html += `<tr><th>NIM</th><td>${formData.get('nim')}</td></tr>`;
    html += `<tr><th>Nama</th><td>${formData.get('nama')}</td></tr>`;
    html += `<tr><th>Email</th><td>${formData.get('email')}</td></tr>`;
    html += `<tr><th>Tanggal Lahir</th><td>${formData.get('tanggal_lahir')}</td></tr>`;
    html += `<tr><th>Jenis Kelamin</th><td>${formData.get('jenis_kelamin') === 'L' ? 'Laki-laki' : 'Perempuan'}</td></tr>`;
    html += `<tr><th>Alamat</th><td>${formData.get('alamat') || '-'}</td></tr>`;
    html += '</table>';
    
    document.getElementById('previewContent').innerHTML = html;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

function submitForm() {
    document.getElementById('formTambah').submit();
}
</script>

<?php include 'includes/footer.php'; ?>
