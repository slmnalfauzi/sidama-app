<?php
require_once 'db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Statistik dashboard
$stmt = $pdo->query("SELECT COUNT(*) as total FROM mahasiswa");
$total_mahasiswa = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT AVG(YEAR(CURDATE()) - YEAR(tanggal_lahir)) as rata_umur FROM mahasiswa");
$rata_umur = round($stmt->fetch()['rata_umur'], 1);

$stmt = $pdo->query("SELECT MIN(tanggal_lahir) as tertua FROM mahasiswa");
$tertua = $stmt->fetch()['tertua'];
$umur_tertua = $tertua ? hitungUmur($tertua) : 0;

$stmt = $pdo->query("SELECT MAX(tanggal_lahir) as termuda FROM mahasiswa");
$termuda = $stmt->fetch()['termuda'];
$umur_termuda = $termuda ? hitungUmur($termuda) : 0;

$stmt = $pdo->query("SELECT COUNT(*) as baru FROM mahasiswa WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$mahasiswa_baru = $stmt->fetch()['baru'];

// Data mahasiswa terbaru
$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY created_at DESC LIMIT 5");
$mahasiswa_terbaru = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Dashboard</h2>
    </div>
</div>

 <!-- Statistik Cards  -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Mahasiswa</h6>
                        <h2 class="card-title mb-0"><?= $total_mahasiswa ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Rata-rata Umur</h6>
                        <h2 class="card-title mb-0"><?= $rata_umur ?> <small>tahun</small></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Termuda / Tertua</h6>
                        <h2 class="card-title mb-0"><?= $umur_termuda ?> / <?= $umur_tertua ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-birthday-cake fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Baru Minggu Ini</h6>
                        <h2 class="card-title mb-0"><?= $mahasiswa_baru ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-user-plus fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- Mahasiswa Terbaru  -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Mahasiswa Terbaru</h5>
            </div>
            <div class="card-body">
                <?php if (count($mahasiswa_terbaru) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Umur</th>
                                <th>Ditambahkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mahasiswa_terbaru as $mhs): ?>
                            <tr>
                                <td>
                                    <img src="uploads/<?= clean($mhs['foto']) ?>" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                </td>
                                <td><?= clean($mhs['nim']) ?></td>
                                <td><?= clean($mhs['nama']) ?></td>
                                <td><?= clean($mhs['email']) ?></td>
                                <td><?= hitungUmur($mhs['tanggal_lahir']) ?> tahun</td>
                                <td><?= date('d/m/Y H:i', strtotime($mhs['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data mahasiswa</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
