<?php
require 'koneksi.php';

if (isset($_POST['register'])) {
    $nama_booth = $_POST['nama_booth'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah terpakai!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, nama_booth) VALUES ('$username', '$password_hash', '$nama_booth')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booth Baru - Unsrat Foodcourt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 400px;
        }
        .register-header {
            background: #fff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .btn-register {
            background: linear-gradient(to right, #11998e, #38ef7d);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
        }
        .btn-register:hover { opacity: 0.9; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="register-card p-4">
        <div class="register-header mb-3">
            <h4 class="fw-bold text-success">Registrasi Tenant</h4>
            <p class="text-muted small mb-0">Daftarkan Booth Baru di Foodcourt</p>
        </div>

        <?php if(isset($error)) : ?>
            <div class="alert alert-danger text-center p-2 small">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Booth (Toko)</label>
                <input type="text" name="nama_booth" class="form-control" placeholder="Contoh: Bakso Solo" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username login" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password aman" required>
            </div>
            
            <button type="submit" name="register" class="btn btn-register w-100 rounded-pill mt-2">DAFTAR SEKARANG</button>
            
            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-muted">Sudah punya akun? Login</a>
            </div>
        </form>
    </div>
</body>
</html>