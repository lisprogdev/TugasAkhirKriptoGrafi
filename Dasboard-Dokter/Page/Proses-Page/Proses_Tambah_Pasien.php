<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

function generateIdPasien() {
    return "PSN-" . bin2hex(random_bytes(4)) . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validasi input wajib
        $requiredFields = ['namaLengkap', 'username', 'password', 'alamat'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field wajib diisi!");
            }
        }

        $koneksi->begin_transaction();

        // Inisialisasi data
        $idPasien = generateIdPasien();
        $namaLengkap = mysqli_real_escape_string($koneksi, $_POST['namaLengkap']);
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

        // Simpan data ke database
        $stmt = $koneksi->prepare(
            "INSERT INTO ds_pengguna_tb (id_pengguna, nama_lengkap, username, password, alamat, hak_akses) 
             VALUES (?, ?, ?, ?, ?, 'Pasien')"
        );
        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query insert: " . $koneksi->error);
        }

        $stmt->bind_param('sssss', $idPasien, $namaLengkap, $username, $password, $alamat);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data: " . $stmt->error);
        }

        $koneksi->commit();
        $_SESSION['success_pasien'] = "Data pasien berhasil disimpan!";
    } catch (Exception $e) {
        if (isset($koneksi)) {
            $koneksi->rollback();
        }
        $_SESSION['error_pasien'] = $e->getMessage();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($koneksi)) $koneksi->close();
        header("Location: ../Pasien.php");
        exit;
    }
}
?>
