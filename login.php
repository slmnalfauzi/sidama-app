<?php
session_start();
require_once 'db.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            redirect('index.php');
        } else {
            $error = 'Username atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDAMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                            <h3 class="fw-bold">SIDAMA</h3>
                            <p class="text-muted">Sistem Informasi Data Mahasiswa</p>
                        </div>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="username" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                Demo: admin/password atau viewer/password
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
