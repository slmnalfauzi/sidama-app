<?php
require_once 'db.php';
require_once 'includes/functions.php';
require_once 'auth.php';

requireAdmin();

$errors = [];
$success = false;
$imported = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if ($extension === 'csv') {
            $handle = fopen($file['tmp_name'], 'r');
            
            // Skip header
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) >= 6) {
                    $nim = trim($data[0]);
                    $nama = trim($data[1]);
                    $email = trim($data[2]);
                    $tanggal_lahir = trim($data[3]);
                    $jenis_kelamin = trim($data[4]);
                    $alamat = trim($data[5]);
                    
                    // Validasi dan insert
                    if (validasiNIM($nim) && validasiEmail($email)) {
                        try {
                            $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, email, tanggal_lahir, jenis_kelamin, alamat) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute([$nim, $nama, $email, $tanggal_lahir, $jenis_kelamin, $alamat]);
                            $imported++;
                        } catch (PDOException $e) {
                            // Skip jika NIM duplikat
                        }
                    }
                }
            }
            
            fclose($handle);
            $success = true;
        } else {
            $errors[] = 'Format file tidak didukung. Gunakan CSV.';
        }
    } else {
        $errors[] = 'Gagal mengupload file';
    }
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-file-import me-2"></i>Import Data Mahasiswa</h5>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>Berhasil mengimport <?= $imported ?> data mahasiswa
                </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Format CSV:</h6>
                    <p class="mb-0">NIM, Nama, Email, Tanggal Lahir (YYYY-MM-DD), Jenis Kelamin (L/P), Alamat</p>
                    <p class="mb-0 mt-2"><strong>Contoh:</strong></p>
                    <code>2021004,John Doe,john@email.com,2003-01-15,L,Jl. Example No. 1</code>
                </div>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Pilih File CSV</label>
                        <input type="file" class="form-control" name="file" accept=".csv" required>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload me-1"></i>Import
                        </button>
                        <a href="mahasiswa.php" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <h6>Download Template CSV</h6>
                <a href="template.csv" class="btn btn-outline-primary btn-sm" download>
                    <i class="fas fa-download me-1"></i>Download Template
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
