<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

function generateIdPengguna() {
    return "PRW-" . bin2hex(random_bytes(4)) . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $requiredFields = ['namaLengkap', 'alamat', 'username', 'password', 'idKunci', 'tandaTangan'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field wajib diisi!");
            }
        }

        $koneksi->begin_transaction();

        $idPengguna = generateIdPengguna();
        $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
        $namaLengkap = mysqli_real_escape_string($koneksi, $_POST['namaLengkap']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $username = mysqli_real_escape_string($koneksi, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $idKunci = mysqli_real_escape_string($koneksi, $_POST['idKunci']);
        $hakAkses = 'Perawat';

        $stmt = $koneksi->prepare("SELECT kunci_privat, kunci_publik FROM ds_kunci_tb WHERE id_kunci = ?");
        if (!$stmt) {
            throw new Exception("Terjadi kesalahan saat mempersiapkan query kunci: " . $koneksi->error);
        }
        $stmt->bind_param('s', $idKunci);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("ID kunci tidak ditemukan");
        }

        $row = $result->fetch_assoc();
        $privateKey = "-----BEGIN DSA PRIVATE KEY-----\n" . 
                      chunk_split($row['kunci_privat'], 64, "\n") .
                      "-----END DSA PRIVATE KEY-----";
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . 
                     chunk_split($row['kunci_publik'], 64, "\n") .
                     "-----END PUBLIC KEY-----";

        $privateKeyResource = openssl_pkey_get_private($privateKey);
        $publicKeyResource = openssl_pkey_get_public($publicKey);

        if (!$privateKeyResource || !$publicKeyResource) {
            throw new Exception("Format kunci DSA tidak valid");
        }

        // Proses unggah foto profil
        $targetDir = '../../../Img/';
        $imageFileType = strtolower(pathinfo($_FILES['fotoProfil']['name'], PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            throw new Exception("Format file foto tidak valid");
        }
        $fotoProfil = uniqid() . "." . $imageFileType;
        if (!move_uploaded_file($_FILES['fotoProfil']['tmp_name'], $targetDir . $fotoProfil)) {
            throw new Exception("Gagal mengunggah foto");
        }

        // Proses tanda tangan
        $signatureData = base64_decode(substr($_POST['tandaTangan'], strpos($_POST['tandaTangan'], ',') + 1));
        if (!$signatureData) {
            throw new Exception("Data tanda tangan tidak valid");
        }

        // Simpan tanda tangan sebagai gambar
        $signatureImageFile = "ttd_" . $idPengguna . "_" . $idKunci . ".png";
        if (!file_put_contents($targetDir . $signatureImageFile, $signatureData)) {
            throw new Exception("Gagal menyimpan tanda tangan sebagai gambar");
        }

        // Buat hash untuk tanda tangan
        $hash = hash('sha256', $signatureData, true);
        if (!openssl_sign($hash, $signature, $privateKeyResource, OPENSSL_PKCS1_PADDING)) {
            throw new Exception("Gagal membuat tanda tangan digital");
        }
        if (!openssl_verify($hash, $signature, $publicKeyResource, OPENSSL_PKCS1_PADDING)) {
            throw new Exception("Verifikasi tanda tangan digital gagal");
        }

        // Simpan tanda tangan digital sebagai file .sig
        $signatureFile = "ttd_" . $idPengguna . "_" . $idKunci . ".sig";
        if (!file_put_contents($targetDir . $signatureFile, base64_encode($signature))) {
            throw new Exception("Gagal menyimpan file tanda tangan digital");
        }

        // Simpan ke database
        $stmt = $koneksi->prepare(
            "INSERT INTO ds_pengguna_tb (id_pengguna, nama_lengkap, alamat, nip, username, password, 
             foto_profil, hak_akses, id_kunci, tanda_tangan) VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query insert: " . $koneksi->error);
        }

        $stmt->bind_param('ssssssssss', 
            $idPengguna, $namaLengkap, $alamat, $nip, $username, $password, 
            $fotoProfil, $hakAkses, $idKunci, $signatureFile
        );
        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data: " . $stmt->error);
        }

        $koneksi->commit();
        $_SESSION['success_perawat'] = "Data perawat berhasil disimpan!";
    } catch (Exception $e) {
        if (isset($koneksi)) {
            $koneksi->rollback();
        }
        $_SESSION['error_perawat'] = $e->getMessage();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($koneksi)) $koneksi->close();
        header("Location: ../Perawat.php");
        exit;
    }
}
?>
