<?php
session_start(); // Memulai session
include '../../../Koneksi-DSA/Koneksi-DSA.php'; // Ganti dengan file koneksi Anda.

if (isset($_GET['id'])) {
    $id_laporan_pasien = intval($_GET['id']);

    // Query untuk mendapatkan data laporan berdasarkan ID
    $query = "SELECT * FROM ds_laporan_pasien_tb WHERE id_laporan_pasien = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_laporan_pasien);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tanda_tangan = $row['tanda_tangan']; // Nama file tanda tangan
        $img_path = "../../../Img/" . $tanda_tangan;

        if (file_exists($img_path)) {
            // Cek apakah nama file memiliki ekstensi .sig
            $file_extension = pathinfo($img_path, PATHINFO_EXTENSION);

            if (strcasecmp($file_extension, "sig") === 0) {
                $_SESSION['success_laporan'] = "Nama file tanda tangan cocok dengan ekstensi .sig.";
            } else {
                $_SESSION['error_laporan'] = "Nama file tanda tangan tidak cocok dengan ekstensi .sig.";
            }
        } else {
            $_SESSION['error_laporan'] = "File tanda tangan tidak ditemukan.";
        }
    } else {
        $_SESSION['error_laporan'] = "Laporan tidak ditemukan.";
    }
} else {
    $_SESSION['error_laporan'] = "ID laporan tidak valid.";
}

// Redirect ke halaman lain untuk menampilkan pesan
header("Location: ../Laporan.php");
exit();
