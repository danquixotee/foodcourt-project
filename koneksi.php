<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_unsrat_foodcourt";
$port = 3307; // normal 3306

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>