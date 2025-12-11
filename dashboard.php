<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: index.php"); exit; }
$user_id = $_SESSION['user_id'];
$nama_booth = $_SESSION['nama_booth'];

$swal_icon = ""; $swal_title = ""; $swal_text = "";

if (isset($_POST['tambah_barang'])) {
    $nama = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $query = mysqli_query($conn, "INSERT INTO items (user_id, nama_barang, satuan, stok) VALUES ('$user_id', '$nama', '$satuan', 0)");
    if($query) { $swal_icon = "success"; $swal_title = "Berhasil!"; $swal_text = "Barang baru ditambahkan."; }
}

if (isset($_POST['update_stok'])) {
    $id = $_POST['item_id'];
    $jumlah = $_POST['jumlah'];
    $tipe = $_POST['update_stok']; 

    if ($tipe == 'masuk') {
        mysqli_query($conn, "UPDATE items SET stok = stok + $jumlah WHERE item_id = '$id' AND user_id = '$user_id'");
        mysqli_query($conn, "INSERT INTO transactions (user_id, item_id, tipe_transaksi, jumlah) VALUES ('$user_id', '$id', 'masuk', '$jumlah')");
        $swal_icon = "success"; $swal_title = "Stok Masuk!"; $swal_text = "Stok berhasil ditambahkan.";
    } else if ($tipe == 'keluar') {
        $cek = mysqli_query($conn, "SELECT stok FROM items WHERE item_id = '$id'");
        $data = mysqli_fetch_assoc($cek);
        if ($data['stok'] >= $jumlah) {
            mysqli_query($conn, "UPDATE items SET stok = stok - $jumlah WHERE item_id = '$id' AND user_id = '$user_id'");
            mysqli_query($conn, "INSERT INTO transactions (user_id, item_id, tipe_transaksi, jumlah) VALUES ('$user_id', '$id', 'keluar', '$jumlah')");
            $swal_icon = "success"; $swal_title = "Stok Keluar!"; $swal_text = "Stok berhasil dikurangi.";
        } else {
            $swal_icon = "error"; $swal_title = "Gagal!"; $swal_text = "Stok tidak mencukupi.";
        }
    }
}

if (isset($_POST['hapus_barang'])) {
    $id_hapus = $_POST['item_id_hapus'];

    $cek_milik = mysqli_query($conn, "SELECT * FROM items WHERE item_id='$id_hapus' AND user_id='$user_id'");
    
    if(mysqli_num_rows($cek_milik) > 0) {
        mysqli_query($conn, "DELETE FROM transactions WHERE item_id='$id_hapus'");
        
        $hapus = mysqli_query($conn, "DELETE FROM items WHERE item_id='$id_hapus'");
        
        if($hapus) {
            $swal_icon = "success"; $swal_title = "Terhapus!"; $swal_text = "Barang dan riwayatnya telah dihapus.";
        }
    } else {
        $swal_icon = "error"; $swal_title = "Gagal!"; $swal_text = "Anda tidak berhak menghapus barang ini.";
    }
}

$items = mysqli_query($conn, "SELECT * FROM items WHERE user_id = '$user_id'");

$total_jenis = mysqli_num_rows($items);
$total_stok_fisik = 0;
$perlu_restock = 0;
$data_barang = [];
while($row = mysqli_fetch_assoc($items)) {
    $total_stok_fisik += $row['stok'];
    if($row['stok'] < 5) $perlu_restock++;
    $data_barang[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - <?= $nama_booth ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-image: url('bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        .navbar, .card, .stat-card {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(5px);
        }
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .table-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        .btn-action { border-radius: 8px; font-weight: 500; }
        .badge-stok { font-size: 0.9em; padding: 8px 12px; border-radius: 8px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="#">
                <i class="bi bi-shop-window me-2"></i> Unsrat Foodcourt
            </a>
            <div class="d-flex align-items-center">
                <div class="text-end me-3 d-none d-md-block">
                    <div class="fw-bold text-dark"><?= $nama_booth ?></div>
                    <div class="small text-muted">Tenant Admin</div>
                </div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-3"><i class="bi bi-box-seam"></i></div>
                        <div><h6 class="text-muted mb-0">Total Jenis Barang</h6><h3 class="fw-bold mb-0"><?= $total_jenis ?></h3></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success me-3"><i class="bi bi-layers"></i></div>
                        <div><h6 class="text-muted mb-0">Total Unit Stok</h6><h3 class="fw-bold mb-0"><?= $total_stok_fisik ?></h3></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-3"><i class="bi bi-exclamation-circle"></i></div>
                        <div><h6 class="text-muted mb-0">Perlu Restock (< 5)</h6><h3 class="fw-bold mb-0 text-danger"><?= $perlu_restock ?></h3></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header bg-white py-3 fw-bold border-bottom-0">
                        <i class="bi bi-plus-circle-dotted me-2 text-primary"></i> Tambah Barang
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Roti Tawar" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Satuan</label>
                                <select name="satuan" class="form-select">
                                    <option value="Pcs">Pcs</option>
                                    <option value="Bungkus">Bungkus</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Liter">Liter</option>
                                </select>
                            </div>
                            <button type="submit" name="tambah_barang" class="btn btn-primary w-100 py-2 rounded-3">
                                <i class="bi bi-save me-1"></i> Simpan ke Database
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-4">
                <div class="card table-card h-100">
                    <div class="card-header bg-white py-3 fw-bold border-bottom-0 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-list-task me-2 text-primary"></i> Daftar Inventori</span>
                        <span class="badge bg-light text-dark border"><?= date('d M Y') ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Item</th>
                                        <th class="text-center">Stok</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($data_barang) > 0): ?>
                                        <?php foreach($data_barang as $row) : ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                            <td class="text-center">
                                                <?php $badge = $row['stok'] < 5 ? 'bg-danger bg-opacity-10 text-danger border border-danger' : 'bg-success bg-opacity-10 text-success border border-success'; ?>
                                                <span class="badge badge-stok <?= $badge ?>"><?= $row['stok']; ?></span>
                                            </td>
                                            <td class="text-muted small"><?= $row['satuan']; ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form method="post" class="d-flex gap-1">
                                                        <input type="hidden" name="item_id" value="<?= $row['item_id']; ?>">
                                                        <input type="number" name="jumlah" class="form-control form-control-sm text-center" value="1" style="width: 50px;" required min="1">
                                                        <button type="submit" name="update_stok" value="masuk" class="btn btn-sm btn-light text-primary border btn-action" title="Masuk"><i class="bi bi-plus-lg"></i></button>
                                                        <button type="submit" name="update_stok" value="keluar" class="btn btn-sm btn-light text-danger border btn-action" title="Keluar"><i class="bi bi-dash-lg"></i></button>
                                                    </form>
                                                    
                                                    <button onclick="konfirmasiHapus(<?= $row['item_id']; ?>, '<?= $row['nama_barang']; ?>')" class="btn btn-sm btn-danger btn-action" title="Hapus Barang">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="formHapus" method="post" style="display:none;">
        <input type="hidden" name="item_id_hapus" id="inputHapusId">
        <input type="hidden" name="hapus_barang" value="1">
    </form>

    <script>
        function konfirmasiHapus(id, nama) {
            Swal.fire({
                title: 'Hapus ' + nama + '?',
                text: "Data stok dan riwayat transaksi barang ini akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('inputHapusId').value = id;
                    document.getElementById('formHapus').submit();
                }
            })
        }
    </script>

    <?php if($swal_icon != ""): ?>
    <script>
        Swal.fire({
            icon: '<?= $swal_icon ?>',
            title: '<?= $swal_title ?>',
            text: '<?= $swal_text ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>