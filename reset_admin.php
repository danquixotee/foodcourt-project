<?php
require 'koneksi.php';

$password_plain = "adminfoodcourtunsrat123";

$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

$username = "admin";

$query = "UPDATE users SET password = '$password_hash' WHERE username = '$username'";

if (mysqli_query($conn, $query)) {
    echo "<h1>SUKSES!</h1>";
    echo "Password untuk user <b>$username</b> telah diubah menjadi: <b>$password_plain</b><br><br>";
    echo "Hash baru di database: <small>$password_hash</small><br><br>";
    echo "<a href='index.php'>Klik di sini untuk Login Admin</a>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>