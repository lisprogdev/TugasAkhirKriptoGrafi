<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['keyId'])) {
        $keyId = $_POST['keyId'];

        // Pastikan $keyId aman
        $keyId = $koneksi->real_escape_string($keyId);

        // Lokasi folder tempat file kunci disimpan
        $keyDir = './Keys/';
        $privateKeyFile = $keyDir . $keyId . '_private_key.pem';
        $publicKeyFile = $keyDir . $keyId . '_public_key.pem';
        $dsaParamFile = $keyDir . $keyId . '_dsa_params.pem';

        // Hapus data dari database
        $sql = "DELETE FROM ds_kunci_tb WHERE id_kunci = '$keyId'";
        if ($koneksi->query($sql) === TRUE) {
            // Hapus file terkait kunci jika ada
            $filesDeleted = true;

            if (file_exists($privateKeyFile) && !unlink($privateKeyFile)) {
                $filesDeleted = false;
            }

            if (file_exists($publicKeyFile) && !unlink($publicKeyFile)) {
                $filesDeleted = false;
            }

            if (file_exists($dsaParamFile) && !unlink($dsaParamFile)) {
                $filesDeleted = false;
            }

            if ($filesDeleted) {
                $_SESSION['success_key'] = "Kunci dan file terkait berhasil dihapus.";
            } else {
                $_SESSION['error_key'] = "Kunci dihapus, tetapi beberapa file gagal dihapus.";
            }
        } else {
            $_SESSION['error_key'] = "Terjadi kesalahan saat menghapus kunci: " . $koneksi->error;
        }
    } else {
        $_SESSION['error_key'] = "ID kunci tidak valid.";
    }

    $koneksi->close();
    header("Location: ../Kunci.php");
    exit;
}
?>
