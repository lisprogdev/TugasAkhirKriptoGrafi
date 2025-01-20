<?php
// Konfigurasi database
$host = "localhost";      // Alamat server database
$user = "root";           // Username database
$password = "";           // Password database
$dbname = "dsa_dokter_azwar"; // Nama database

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $password, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
