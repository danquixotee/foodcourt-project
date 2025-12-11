<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') { 
    header("Location: index.php"); exit; 
}

if (isset($_POST['tambah_booth'])) {
    $nama = $_POST['nama_booth'];
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $cek = mysqli_query($conn, "SELECT username FROM users WHERE username = '$user'");
    if(mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO users (nama_booth, username, password, role) VALUES ('$nama', '$user', '$pass', 'tenant')");
        echo "<script>alert('Booth Berhasil Ditambahkan!');</script>";
    } else {
        echo "<script>alert('Username sudah dipakai!');</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM transactions WHERE user_id = '$id'");

    mysqli_query($conn, "DELETE FROM items WHERE user_id = '$id'");

    // (Opsional) Hapus Kategori jika menggunakan tabel categories
    // mysqli_query($conn, "DELETE FROM categories WHERE user_id = '$id'");

    $hapus_user = mysqli_query($conn, "DELETE FROM users WHERE user_id = '$id'");

    if($hapus_user) {
        echo "<script>alert('Booth dan seluruh datanya berhasil dihapus!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus: ".mysqli_error($conn)."'); window.location='admin_dashboard.php';</script>";
    }
}

$tenants = mysqli_query($conn, "SELECT * FROM users WHERE role = 'tenant'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Panel - Unsrat Foodcourt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f3f4f6; }</style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark py-3">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="bi bi-shield-lock"></i> PANEL PENGELOLA FOODCOURT</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">+ Daftarkan Booth Baru</div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label>Nama Booth</label>
                                <input type="text" name="nama_booth" class="form-control" placeholder="Contoh: Es Teh Jaya" required>
                            </div>
                            <div class="mb-3">
                                <label>Username Login</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password Awal</label>
                                <input type="text" name="password" class="form-control" placeholder="123" required>
                            </div>
                            <button type="submit" name="tambah_booth" class="btn btn-primary w-100">Buat Akun Booth</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-bold py-3">Daftar Penyewa (Tenant) Aktif</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Booth</th>
                                    <th>Username</th>
                                    <th>Terdaftar Sejak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; while($row = mysqli_fetch_assoc($tenants)): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($row['nama_booth']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td class="small text-muted"><?= $row['created_at'] ?? '-' ?></td>
                                    <td>
                                        <a href="admin_dashboard.php?hapus=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus booth ini beserta seluruh datanya?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>