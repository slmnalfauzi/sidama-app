<?php
require_once 'db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Search & Filter
$search = $_GET['search'] ?? '';
$filter_umur = $_GET['filter_umur'] ?? '';
$sort = $_GET['sort'] ?? 'nama';
$order = $_GET['order'] ?? 'ASC';

// Build query
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(nama LIKE ? OR nim LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($filter_umur)) {
    $where[] = "YEAR(CURDATE()) - YEAR(tanggal_lahir) = ?";
    $params[] = $filter_umur;
}

$where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Count total
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM mahasiswa $where_clause");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

// Get data
$allowed_sort = ['nim', 'nama', 'email', 'tanggal_lahir'];
$sort = in_array($sort, $allowed_sort) ? $sort : 'nama';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

$stmt = $pdo->prepare("SELECT * FROM mahasiswa $where_clause ORDER BY $sort $order LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$mahasiswa = $stmt->fetchAll();

$pagination = getPagination($total, $per_page, $page);
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-users me-2"></i>Data Mahasiswa</h2>
    </div>
</div>

 <!-- Filter & Search  -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Cari NIM atau Nama..." value="<?= clean($search) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="filter_umur">
                    <option value="">Semua Umur</option>
                    <?php for ($i = 18; $i <= 30; $i++): ?>
                    <option value="<?= $i ?>" <?= $filter_umur == $i ? 'selected' : '' ?>><?= $i ?> tahun</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="mahasiswa.php" class="btn btn-secondary w-100">
                    <i class="fas fa-redo me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

 <!-- Action Buttons  -->
<?php if (isAdmin()): ?>
<div class="mb-3">
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Mahasiswa
    </a>
    <a href="import.php" class="btn btn-success">
        <i class="fas fa-file-import me-1"></i>Import Data
    </a>
    <a href="export.php" class="btn btn-info text-white">
        <i class="fas fa-file-export me-1"></i>Export Data
    </a>
</div>
<?php endif; ?>

 <!-- Tabel Data  -->
<div class="card">
    <div class="card-body">
        <?php if (count($mahasiswa) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>
                            <a href="?sort=nim&order=<?= $sort === 'nim' && $order === 'ASC' ? 'DESC' : 'ASC' ?>&search=<?= $search ?>&filter_umur=<?= $filter_umur ?>" class="text-decoration-none text-dark">
                                NIM <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=nama&order=<?= $sort === 'nama' && $order === 'ASC' ? 'DESC' : 'ASC' ?>&search=<?= $search ?>&filter_umur=<?= $filter_umur ?>" class="text-decoration-none text-dark">
                                Nama <i class="fas fa-sort"></i>
                            </a>
                        </th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td>
                            <img src="uploads/<?= clean($mhs['foto']) ?>" alt="Foto" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                        </td>
                        <td><?= clean($mhs['nim']) ?></td>
                        <td><?= clean($mhs['nama']) ?></td>
                        <td><?= clean($mhs['email']) ?></td>
                        <td><?= formatTanggal($mhs['tanggal_lahir']) ?></td>
                        <td><?= hitungUmur($mhs['tanggal_lahir']) ?> tahun</td>
                        <td><?= $mhs['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                        <td>
                            <button class="btn btn-sm btn-info text-white" onclick="viewDetail(<?= $mhs['id'] ?>)" data-bs-toggle="tooltip" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if (isAdmin()): ?>
                            <a href="edit.php?id=<?= $mhs['id'] ?>" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $mhs['id'] ?>, '<?= clean($mhs['nama']) ?>')" data-bs-toggle="tooltip" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
         <!-- Pagination  -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= !$pagination['has_prev'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination['prev_page'] ?>&search=<?= $search ?>&filter_umur=<?= $filter_umur ?>&sort=<?= $sort ?>&order=<?= $order ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>&filter_umur=<?= $filter_umur ?>&sort=<?= $sort ?>&order=<?= $order ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $pagination['next_page'] ?>&search=<?= $search ?>&filter_umur=<?= $filter_umur ?>&sort=<?= $sort ?>&order=<?= $order ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <p class="text-muted">Tidak ada data mahasiswa</p>
        </div>
        <?php endif; ?>
    </div>
</div>

 <!-- Modal Detail  -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    modal.show();
    
    fetch(`get_detail.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const mhs = data.data;
                document.getElementById('detailContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="uploads/${mhs.foto}" class="img-fluid rounded mb-3" alt="Foto">
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr><th>NIM</th><td>${mhs.nim}</td></tr>
                                <tr><th>Nama</th><td>${mhs.nama}</td></tr>
                                <tr><th>Email</th><td>${mhs.email}</td></tr>
                                <tr><th>Tanggal Lahir</th><td>${mhs.tanggal_lahir_formatted}</td></tr>
                                <tr><th>Umur</th><td>${mhs.umur} tahun</td></tr>
                                <tr><th>Jenis Kelamin</th><td>${mhs.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td></tr>
                                <tr><th>Alamat</th><td>${mhs.alamat || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
            }
        });
}

function confirmDelete(id, nama) {
    Swal.fire({
        title: 'Hapus Data?',
        text: `Apakah Anda yakin ingin menghapus data ${nama}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete.php?id=${id}`;
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
