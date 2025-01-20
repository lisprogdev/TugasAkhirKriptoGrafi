<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyId = $_POST['keyId'];
    $keyLength = $_POST['keyLength'];
    $keyCode = $_POST['keyCode'];

    // Validasi format kode_kunci
    if (!preg_match('/^\d{3} - [A-Za-z]{1,3}$/', $keyCode)) {
        $_SESSION['error_key'] = "Format kode kunci tidak valid! Harus berupa 3 digit angka diikuti dengan 1 hingga 3 huruf.";
        header("Location: ../Kunci.php");
        exit;
    }

    // Validasi panjang kunci
    $keyLengthMap = ['1024' => 1024, '2048' => 2048, '4096' => 4096];
    if (!isset($keyLengthMap[$keyLength])) {
        $_SESSION['error_key'] = "Panjang kunci tidak valid!";
        header("Location: ../Kunci.php");
        exit;
    }

    // Perbarui data di database
    $sql = "UPDATE ds_kunci_tb SET panjang_kunci = ?, kode_kunci = ? WHERE id_kunci = ?";
    $stmt = $koneksi->prepare($sql);
    if ($stmt === false) {
        $_SESSION['error_key'] = "Gagal menyiapkan query: " . $koneksi->error;
        header("Location: ../Kunci.php");
        exit;
    }

    $stmt->bind_param("sss", $keyLengthMap[$keyLength], $keyCode, $keyId);

    if ($stmt->execute()) {
        $_SESSION['success_key'] = "Kunci berhasil diperbarui.";
    } else {
        $_SESSION['error_key'] = "Terjadi kesalahan saat memperbarui kunci: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();

    header("Location: ../Kunci.php");
    exit;
}
?>
