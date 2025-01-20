<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validasi input wajib
        $requiredFields = ['id_pengguna', 'keluhan', 'saran', 'tanda_tangan'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field wajib diisi!");
            }
        }

        // Ambil data dari form
        $idPasien = mysqli_real_escape_string($koneksi, $_POST['id_pengguna']);
        $keluhan = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
        $saran = mysqli_real_escape_string($koneksi, $_POST['saran']);
        $tandaTangan = mysqli_real_escape_string($koneksi, $_POST['tanda_tangan']);

        $koneksi->begin_transaction();

        // Query untuk menyimpan laporan ke tabel ds_laporan_pasien_tb
        $stmt = $koneksi->prepare(
            "INSERT INTO ds_laporan_pasien_tb (id_pasien, keluhan, saran, tanda_tangan, created_at, updated_at) 
             VALUES (?, ?, ?, ?, NOW(), NOW())"
        );
        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query insert: " . $koneksi->error);
        }

        // Bind parameter dan eksekusi query
        $stmt->bind_param('ssss', $idPasien, $keluhan, $saran, $tandaTangan);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan laporan: " . $stmt->error);
        }

        $koneksi->commit();
        $_SESSION['success_pasien'] = "Laporan berhasil dikirim!";
    } catch (Exception $e) {
        if (isset($koneksi)) {
            $koneksi->rollback();
        }
        $_SESSION['error_pasien'] = $e->getMessage();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($koneksi)) $koneksi->close();
        header("Location: ../Pasien.php"); // Redirect ke halaman pasien setelah proses
        exit;
    }
}
?>
