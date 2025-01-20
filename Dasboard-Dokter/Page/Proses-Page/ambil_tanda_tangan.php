<?php
// Koneksi ke database
include('../../../Koneksi-DSA/Koneksi-DSA.php');

// Ambil ID pengguna dari request
$id_pengguna = $_POST['id_pengguna'];

// Query untuk mengambil tanda tangan berdasarkan hak akses 'Perawat'
$query = "SELECT tanda_tangan FROM ds_pengguna_tb WHERE id_pengguna = ? AND hak_akses = 'Perawat'";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$stmt->bind_result($tanda_tangan);
$stmt->fetch();

// Mengembalikan tanda tangan sebagai response
echo $tanda_tangan;

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>
